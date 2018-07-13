<?php

namespace kartavik\Designer;

/**
 * Class BaseCollection
 * @package kartavik\Designer
 */
class BaseCollection extends \ArrayObject implements \JsonSerializable, CollectionInterface
{
    /**
     * Type of collection's element
     */
    public const ELEMENT_TYPE = null;

    /** @var string Type of collection's element */
    protected $type;

    /**
     * BaseCollection constructor.
     *
     * @param array       $elements
     * @param string|null $type
     * @param int         $flags
     * @param string      $iteratorClass
     *
     * @throws NotSetTypeException
     */
    public function __construct(
        array $elements = [],
        string $type = null,
        int $flags = 0,
        string $iteratorClass = \ArrayIterator::class
    ) {
        foreach ($elements as $element) {
            $this->instanceOfType($element);
        }

        parent::__construct($elements, $flags, $iteratorClass);
    }

    /**
     * @return string
     * @throws NotSetTypeException
     */
    public function type(): string
    {
        $type = static::ELEMENT_TYPE;

        if (!is_null($type)) {
            return $type;
        } else if (!is_null($this->type)) {
            return $this->type;
        } else {
            throw new NotSetTypeException();
        }
    }

    /**
     * @param mixed $value
     *
     * @throws NotSetTypeException
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
     * @throws NotSetTypeException
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
     * @throws NotSetTypeException
     */
    public function instanceOfType($object): void
    {
        $type = static::ELEMENT_TYPE;

        if (!is_null($type)) {
            if (!$object instanceof $type) {
                throw new InvalidElementException($object, $type);
            }
        } else if (!is_null($this->type)) {
            if (!$object instanceof $this->type) {
                throw new InvalidElementException($object, $this->type);
            }
        } else {
            throw new NotSetTypeException();
        }
    }
}
