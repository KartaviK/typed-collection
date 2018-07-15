<?php

namespace kartavik\Designer\Tests\Mocks;

/**
 * Class SubElement
 * @package kartavik\Designer\Tests\Mocks
 */
class SubElement
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
