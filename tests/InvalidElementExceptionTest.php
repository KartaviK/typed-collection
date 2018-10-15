<?php

namespace kartavik\Designer\Tests;

use kartavik\Collections;
use PHPUnit\Framework\TestCase;

/**
 * Class InvalidElementExceptionTest
 * @internal
 * @package kartavik\Designer\Tests
 */
class InvalidElementExceptionTest extends TestCase
{
    /** @var Collections\Exceptions\InvalidElementException */
    protected $exception;

    /** @var object */
    protected $object;

    /** @var string */
    protected $needType;

    protected function setUp(): void
    {
        $this->object = new Collections\Tests\Mocks\Element(1);
        $this->needType = get_class(new class
        {
        });
        $this->exception = new Collections\Exceptions\InvalidElementException($this->object, $this->needType);
    }

    public function testGetNeedType(): void
    {
        $this->assertEquals($this->object, $this->exception->getObject());
    }

    public function testGetObject(): void
    {
        $this->assertEquals($this->needType, $this->exception->getNeedType());
    }
}
