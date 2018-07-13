<?php

namespace kartavik\Designer;

/**
 * Class InvalidElementException
 * @package kartavik\Designer
 */
class InvalidElementException extends \InvalidArgumentException
{
    protected $object;

    /** @var string */
    protected $needType;

    /**
     * InvalidElementException constructor.
     *
     * @param                 $object
     * @param string          $needType
     * @param int             $code
     * @param \Throwable|null $previous
     */
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
            "Element {$objectType} must be compatible with " . $needType,
            $code,
            $previous
        );
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    public function getNeedType(): string
    {
        return $this->needType;
    }
}
