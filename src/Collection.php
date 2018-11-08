<?php

namespace kartavik\Support;

/**
 * Class Collection
 * @package kartavik\Support\
 */
class Collection implements \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable
{
    /** @var Strict */
    private $strict = null;

    /** @var array */
    protected $container = [];

    /**
     * Collection constructor.
     *
     * @param Strict $type
     * @param iterable ...$iterables
     *
     * @throws Exception\Validation
     */
    public function __construct(Strict $type, iterable ...$iterables)
    {
        $this->strict = $type;

        foreach ($iterables as $iterable) {
            foreach ($iterable as $index => $item) {
                $this->append($item);
            }
        }
    }

    final public function type(): Strict
    {
        return $this->strict;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->container[$offset];
    }

    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->container);
    }

    /**
     * @param mixed $index
     * @param mixed $value
     *
     * @throws Exception\Validation
     */
    public function offsetSet($index, $value): void
    {
        $this->add($value, $index);
    }

    public function jsonSerialize(): array
    {
        return $this->container;
    }

    /**
     * @param mixed ...$var
     *
     * @return Collection
     * @throws Exception\Validation
     */
    public function append(...$var): Collection
    {
        foreach ($var as $item) {
            $this->add($item);
        }

        return $this;
    }

    public function isCompatible(iterable $collection): bool
    {
        try {
            foreach ($collection as $item) {
                $this->validate($item);
            }
        } catch (Exception\Validation $exception) {
            return false;
        }

        return true;
    }

    public function first()
    {
        reset($this->container);

        return $this->current();
    }

    public function last()
    {
        end($this->container);

        return $this->current();
    }

    /**
     * @param mixed $item
     *
     * @throws Exception\Validation
     */
    public function validate($item): void
    {
        $this->type()->validate($item);
    }

    public function chunk(int $size): Collection
    {
        return Collection::{Collection::class}(array_map(function ($chunk) {
            return new Collection($this->type(), $chunk);
        }, array_chunk($this->container, $size)));
    }

    /**
     * @param \Closure $callback
     *
     * @return Collection
     * @throws Exception\Validation
     */
    public function map(\Closure $callback): Collection
    {
        $fetched = [];

        foreach ($this->container as $item) {
            $fetched[] = call_user_func($callback, $item);
        }

        return new Collection(Strict::strictof(current($fetched)), $fetched);
    }

    /**
     * @param bool $preserveKeys
     *
     * @return Collection
     * @throws Exception\Validation
     */
    public function reverse(bool $preserveKeys = false): Collection
    {
        /** @var Collection $collection */
        $collection = static::{$this->strict->type()}();

        foreach (array_reverse($this->container, $preserveKeys) as $index => $item) {
            $collection->add($item, $index);
        }

        return $collection;
    }

    public function current()
    {
        return current($this->container);
    }

    public function pop()
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

    /**
     * @param mixed $item
     * @param mixed $index
     *
     * @throws Exception\Validation
     */
    public function add($item, $index = null): void
    {
        $this->validate($item);
        $this->container[$index ?? $this->count()] = $item;
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return Collection
     * @throws Exception\Validation
     */
    public static function __callStatic(string $name, array $arguments = [])
    {
        return new static(Strict::{$name}(), ...$arguments);
    }
}
