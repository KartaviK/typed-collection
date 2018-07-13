<?php

namespace kartavik\Designer\Tests\Mocks;

/**
 * Class ElementMock
 * @package kartavik\Designer\Tests\Mocks
 */
class ElementMock
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
