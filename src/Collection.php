<?php

namespace kartavik\Collections;

use kartavik\Collections\Exception;

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
        return isset($this->container[$offset]);
    }

    public function offsetGet($offset): object
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
     * @throws Exception\InvalidElement
     */
    public function offsetSet($index, $value): void
    {
        $this->add($value, $index);
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    public function serialize(): string
    {
        return json_encode($this);
    }

    public function unserialize($serialized): Collection
    {
        $json = json_decode($serialized);

        return new static($json['type'], $json['container']);
    }

    public function isCompatible(iterable $collection): bool
    {
        try {
            foreach ($collection as $item) {
                $this->validate($item);
            }
        } catch (Exception\InvalidElement $exception) {
            return false;
        }

        return true;
    }

    public function first(): object
    {
        reset($this->container);

        return current($this->container);
    }

    public function last(): object
    {
        end($this->container);

        return current($this->container);
    }

    /**
     * @param object $item
     *
     * @throws Exception\InvalidElement
     */
    public function validate(object $item): void
    {
        $type = $this->type();

        if (!$item instanceof $type) {
            throw new Exception\InvalidElement($item, $type);
        }
    }

    public function chunk(int $size): Collection
    {
        /** @var Collection $collection */
        $collection = new static(Collection::class);
        $chunked = array_chunk($this->container, $size);

        foreach ($chunked as $chunk) {
            $collection->append(new Collection($this->type(), $chunk));
        }

        return $collection;
    }

    public function column(\Closure $callback): Collection
    {
        $type = get_class(call_user_func($callback, current($this->container)));
        $collection = new Collection($type);
        $fetched = [];

        foreach ($this->container as $item) {
            $fetched[] = call_user_func($callback, $item);
        }

        $collection->append(...$fetched);

        return $collection;
    }

    public function pop(): object
    {
        return array_pop($this->container);
    }

    public function sum(\Closure $callback)
    {
        $sum = 0;

        foreach ($this as $element) {
            $sum += call_user_func($callback, $element);
        }

        return $sum;
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

    protected static function validateObject(string $type): void
    {
        if (!class_exists($type)) {
            throw new Exception\UnprocessedType($type);
        }
    }
}
