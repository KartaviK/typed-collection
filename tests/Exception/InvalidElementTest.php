<?php

namespace kartavik\Collections\Tests\Exception;

use PHPUnit\Framework\TestCase;
use kartavik\Collections\Exception\InvalidElement;


/**
 * Class InvalidElementTest
 * @package kartavik\Collections\Tests\Exception
 * @coversDefaultClass InvalidElement
 * @internal
 */
class InvalidElementTest extends TestCase
{
    /** @var InvalidElement */
    protected $fakeInvalidElement;

    protected function setUp(): void
    {
        $this->fakeInvalidElement = new InvalidElement(
            new \stdClass(),
            \Exception::class
        );
    }

    public function testGetVar(): void
    {
        $this->assertEquals(new \stdClass(), $this->fakeInvalidElement->getVar());
    }

    public function testGetNeedType(): void
    {
        $this->assertEquals(\Exception::class, $this->fakeInvalidElement->getNeedType());
    }
}
