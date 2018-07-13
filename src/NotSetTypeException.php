<?php

namespace kartavik\Designer;

/**
 * Class NotSetTypeException
 * @package kartavik\Designer
 */
class NotSetTypeException extends \Exception
{
    /**
     * NotSetTypeException constructor.
     *
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct(
            'Type of collection does not set!',
            $code,
            $previous
        );
    }
}
