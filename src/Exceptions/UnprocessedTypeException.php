<?php

namespace kartavik\Collections\Exceptions;

use Throwable;

/**
 * Class UnprocessedTypeException
 * @package kartavik\Collections\Exceptions
 */
class UnprocessedTypeException extends \InvalidArgumentException
{
    /** @var string|null */
    protected $type;

    public function __construct(
        ?string $type,
        string $message = "Type must be declared class",
        int $code = -1,
        Throwable $previous = null
    ) {
        $this->type = $type;

        parent::__construct($message, $code, $previous);
    }

    public function getType(): ?string
    {
        return $this->type;
    }
}
