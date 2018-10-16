<?php

namespace kartavik\Collections;

use kartavik\Collections\Exceptions\InvalidElementException;
use kartavik\Collections\Exceptions\UnprocessedTypeException;

/**
 * Class Collection
 * @package kartavik\Collections
 */
class Collection implements \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable, \Serializable
{
    /** @var string */
    private $type = null;

    /** @var array */
    protected $container = [];

    public function __construct(string $type, iterable ...$iterables)
    {
        $this->type = $type;

        foreach ($iterables as $iterable) {
            foreach ($iterable as $item) {
                $this->add($item);
            }
        }
    }

    final public function type(): string
    {
        return $this->type;
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->container);
    }

    public function offsetGet($offset)
    {
        return $this->container[$offset];
    }

    public function offsetUnset($offset): void
    {
        if ($this->offsetExists($offset)) {
            unset($this->container[$offset]);
        }
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->container);
    }

    public function append(): void
    {
        $values = func_get_args();

        foreach ($values as $item) {
            $this->add($item);
        }
    }

    public static function makeSafe(string $type, iterable ...$iterables)
    {
        $collection = new Collection($type);

        foreach ($iterables as $iterable) {
            foreach ($iterable as $key => $item) {
                $collection->add($item, $key);
            }
        }

        return $collection;
    }

    /**
     * @param mixed $index
     * @param mixed $value
     *
     * @throws InvalidElementException
     */
    public function offsetSet($index, $value): void
    {
        $this->add($value, $index);
    }

    public function serialize()
    {
        return serialize($this->container);
    }

    public function unserialize($serialized, array $options = [])
    {
        $unserialized = unserialize($serialized, $options);

        if (is_iterable($unserialized)) {
            return $this->join($unserialized, true);
        }

        throw new \InvalidArgumentException('Serialized object must be iterable');
    }

    public function join(iterable $iterable, bool $useKeys = false)
    {
        foreach ($iterable as $key => $item) {
            $this->add($item, $useKeys ? $key : null);
        }
    }

    public function jsonSerialize(): array
    {
        return $this->container;
    }

    public function isCompatible($element): bool
    {
        try {
            if ($element instanceof self) {
                return true;
            } elseif (is_array($element)) {
                foreach ($element as $item) {
                    try {
                        $this->validate($item);
                    } catch (InvalidElementException $ex) {
                        return false;
                    }
                }

                return true;
            }
        } catch (\InvalidArgumentException $exception) {
        } finally {
            return false;
        }
    }

    public function first()
    {
        reset($this->container);

        return $this->container[key($this->container)];
    }

    /**
     * @param $item
     *
     * @throws InvalidElementException
     */
    public function validate($item): void
    {
        $type = $this->type();

        if (!$item instanceof $type) {
            throw new InvalidElementException($item, $type);
        }
    }

    public function map(callable $callback): Collection
    {
        $type = get_class(call_user_func(
            $callback,
            $this->first()
        ));

        $elements = array_map($callback, $this->container, array_keys($this->container));

        return Collection::{$type}($elements);
    }

    public function walk(callable $callback): bool
    {
        return array_walk($this->container, $callback);
    }

    public function chunk(int $size): Collection
    {
        $mappedType = get_class($this->offsetGet(0));
        /** @var Collection $collection */
        $collection = Collection::{Collection::class}();
        $chunked = array_chunk($this->jsonSerialize(), $size);

        foreach ($chunked as $index => $chunk) {
            $collection->append(Collection::{$mappedType}());

            foreach ($chunk as $item) {
                $collection[$index]->append($item);
            }
        }

        return $collection;
    }

    public function column(string $property, callable $callback = null): Collection
    {
        $getterType = get_class($this->offsetGet(0)->$property);

        if (!is_null($callback)) {
            /** @var Collection $collection */
            $collection = Collection::{$getterType}();

            foreach ($this->jsonSerialize() as $item) {
                $collection->append(call_user_func($callback, $item->$property));
            }

            return $collection;
        } else {
            return Collection::{$getterType}(array_map(
                function ($item) use ($property) {
                    return $item->$property;
                },
                $this->jsonSerialize()
            ));
        }
    }

    public function pop()
    {
        return array_pop($this->container);
    }

    public function sum(callable $callback)
    {
        $sum = 0;

        foreach ($this as $element) {
            $sum += call_user_func($callback, $element);
        }

        return $sum;
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return Collection
     */
    public static function __callStatic(string $name, array $arguments = [])
    {
        if (!empty($arguments) && is_array($arguments[0])) {
            $arguments = $arguments[0];
        }

        static::validateType($name);

        reset($arguments);

        if (current($arguments) instanceof Collection) {
            return new static($name, ...$arguments);
        } else {
            return new static($name, $arguments);
        }
    }

    public function count(): int
    {
        return count($this->container);
    }

    public function add($item, $index = null): void
    {
        $this->validate($item);
        $this->container[$index ?? $this->count()] = $item;
    }

    protected static function validateType(string $type): void
    {
        if (!class_exists($type)) {
            throw new UnprocessedTypeException($type);
        }
    }
}
