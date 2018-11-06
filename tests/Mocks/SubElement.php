<?php

namespace kartavik\Support\Tests\Mocks;

/**
 * Class SubElement
 * @package kartavik\Support\Tests\Mocks
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
