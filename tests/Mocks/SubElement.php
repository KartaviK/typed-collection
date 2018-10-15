<?php

namespace kartavik\Collections\Tests\Mocks;

/**
 * Class SubElement
 * @package kartavik\Collections\Tests\Mocks
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
