<?php

namespace kartavik\Support\Tests\Mocks;

/**
 * Class Element
 * @package kartavik\Support\Tests\Mocks
 */
class Element
{
    /** @var int */
    protected $value;

    /** @var SubElement|null */
    protected $subElement;

    public function __construct(int $value, SubElement $subElement = null)
    {
        $this->value = $value;
        $this->subElement = $subElement;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getSubElement(): ?SubElement
    {
        return $this->subElement;
    }
}
