<?php

namespace kartavik\Support\Exception;

use kartavik\Support\Exception;

/**
 * Class UnprocessedTypeException
 * @package kartavik\Support\Exceptions
 */
class UnprocessedType extends \InvalidArgumentException implements Exception
{
    /** @var string|null */
    protected $type;

    public function __construct(
        ?string $type,
        int $code = -1,
        \Throwable $previous = null
    ) {
        $this->type = $type;

        parent::__construct("Type must be declared class", $code, $previous);
    }

    public function getType(): ?string
    {
        return $this->type;
    }
}
