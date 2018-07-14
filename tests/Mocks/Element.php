<?php

namespace kartavik\Designer\Tests\Mocks;

/**
 * Class Element
 * @package kartavik\Designer\Tests\Mocks
 */
class Element
{
    /** @var int */
    protected $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
