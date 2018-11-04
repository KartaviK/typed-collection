<?php

namespace kartavik\Collections;

use kartavik\Collections\Exception;

/**
 * Class Collection
 * @package kartavik\Collections
 */
class Collection implements \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable
{
    /** @var string */
    private $type = null;

    /** @var array */
    protected $container = [];

    public function __construct(string $type, iterable ...$iterables)
    {
        static::validateObject($type);

        $this->type = $type;

        foreach ($iterables as $iterable) {
            foreach ($iterable as $index => $item) {
                $this->add($item, $index);
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
        $items = func_get_args();

        foreach ($items as $item) {
            $this->add($item);
        }
    }

    /**
     * @param mixed $index
     * @param mixed $value
     *
     * @throws InvalidElement
     */
    public function offsetSet($index, $value): void
    {
        $this->add($value, $index);
    }

    public function jsonSerialize(): array
    {
        return $this->container;
    }

    public function isCompatible($var): bool
    {
        try {
            if ($var instanceof self) {
                return true;
            } elseif (is_array($var)) {
                foreach ($var as $item) {
                    try {
                        $this->validate($item);
                    } catch (Exception\InvalidElement $ex) {
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
     * @throws InvalidElement
     */
    public function validate($item): void
    {
        $type = $this->type();

        if (!$item instanceof $type) {
            throw new Exception\UnprocessedType($item, $type);
        }
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

        static::validateObject($name);

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

    protected static function validateObject(string $type): void
    {
        if (!class_exists($type)) {
            throw new Exception\UnprocessedType($type);
        }
    }
}
