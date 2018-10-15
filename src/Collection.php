<?php

namespace kartavik\Designer;

/**
 * Class Collection
 * @package kartavik\Designer
 */
class Collection extends \ArrayObject implements \JsonSerializable
{
    /** @var string */
    protected $type = null;

    /**
     * Collection constructor.
     *
     * @param array  $elements
     * @param string $type
     * @param int    $flags
     * @param string $iteratorClass
     */
    protected function __construct(
        array $elements = [],
        string $type = null,
        int $flags = 0,
        string $iteratorClass = \ArrayIterator::class
    ) {
        $this->type = $type;

        foreach ($elements as $element) {
            $this->instanceOfType($element);
        }

        parent::__construct($elements, $flags, $iteratorClass);
    }

    public function type(): string
    {
        return $this->type;
    }

    /**
     * @param mixed $value
     *
     * @throws InvalidElementException
     */
    public function append($value): void
    {
        $this->instanceOfType($value);

        parent::append($value);
    }

    /**
     * @param mixed $index
     * @param mixed $value
     *
     * @throws InvalidElementException
     */
    public function offsetSet($index, $value): void
    {
        $this->instanceOfType($value);

        parent::offsetSet($index, $value);
    }

    public function jsonSerialize(): array
    {
        return (array)$this;
    }

    /**
     * @param $object
     *
     * @throws InvalidElementException
     */
    public function instanceOfType($object): void
    {
        $type = $this->type();

        if (!$object instanceof $type) {
            throw new InvalidElementException($object, $type);
        }
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return Collection
     */
    public static function __callStatic($name, $arguments)
    {
        if (!empty($arguments) && is_array($arguments[0])) {
            $arguments = $arguments[0];
        }

        if (!class_exists($name)) {
            throw new \BadMethodCallException("Class with name {$name} does not exist!");
        }

        return new static($arguments, $name);
    }
}
