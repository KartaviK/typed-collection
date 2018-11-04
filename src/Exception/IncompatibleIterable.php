<?php

namespace kartavik\Collections\Exception;

use kartavik\Collections\Exception;

/**
 * Class IncompatibleIterable
 * @package kartavik\Collections\Exception
 */
class IncompatibleIterable extends \InvalidArgumentException implements Exception
{
    /** @var iterable */
    protected $arr;

    public function __construct(iterable $arr, string $message = '', int $code = 0, \Throwable $previous = null)
    {
        $this->arr = $arr;

        parent::__construct($message, $code, $previous);
    }

    public function getArr(): iterable
    {
        return $this->arr;
    }
}
