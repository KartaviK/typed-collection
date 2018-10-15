<?php

namespace kartavik\Collections\Exceptions;

/**
 * Class InvalidElementException
 * @package kartavik\Collections\Exceptions
 */
class InvalidElementException extends \InvalidArgumentException
{
    /** @var mixed $object */
    protected $object;

    /** @var string */
    protected $needType;

    public function __construct(
        $object,
        string $needType,
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->object = $object;
        $this->needType = $needType;

        $objectType = get_class($object);

        parent::__construct(
            "Element {$objectType} must be instance of " . $needType,
            $code,
            $previous
        );
    }

    public function getObject()
    {
        return $this->object;
    }

    public function getNeedType(): string
    {
        return $this->needType;
    }
}
