<?php

namespace kartavik\Support\Exception;

/**
 * Class Validation
 * @package kartavik\Support\Exception
 */
class Validation extends \Exception
{
    protected $var;

    public function __construct(
        $var = null,
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->var = $var;

        parent::__construct($message, $code, $previous);
    }

    public function getVar()
    {
        return $this->var;
    }
}
