<?php

namespace kartavik\Support;

use kartavik\Support\Method;

/**
 * Class Collection
 * @package kartavik\Support\
 */
class Collection implements \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable
{
    use Method\Append,
        Method\Chunk,
        Method\Column;

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

        return $this->current();
    }

    public function last(): object
    {
        end($this->container);

        return $this->current();
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

    public function map(\Closure $closure, iterable ...$collections): array
    {
        $result = [];
        $count = $this->count();
        $values[] = array_values($this->container);

        foreach ($collections as $index => $collection) {
            if (!$this->isCompatible($collection)) {
                throw new Exception\IncompatibleIterable($collection, 'Given iterable object contain invalid element');
            }

            if ($count !== count($collection)) {
                throw new Exception\IncompatibleIterable(
                    $collection,
                    'Given iterable object must contain same count elements'
                );
            }

            foreach ($collection as $item) {
                $values[$index + 1][] = $item;
            }
        }

        foreach (range(0, $this->count() - 1) as $index) {
            $result[] = call_user_func(
                $closure,
                ...array_map(function (array $collection) use ($index) {
                    return $collection[$index];
                }, $values)
            );
        }

        return $result;
    }

    public function reverse(bool $preserveKeys = false): Collection
    {
        return new static($this->type(), array_reverse($this->container, $preserveKeys));
    }
    
    public function current()
    {
        return current($this->container);
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
     * @param array $arguments
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
