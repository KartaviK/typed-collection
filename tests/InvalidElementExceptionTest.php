<?php

namespace kartavik\Designer\Tests;

use kartavik\Designer\InvalidElementException;
use kartavik\Designer\Tests\Mocks\Element;
use PHPUnit\Framework\TestCase;

/**
 * Class InvalidElementExceptionTest
 * @internal
 * @package kartavik\Designer\Tests
 */
class InvalidElementExceptionTest extends TestCase
{
    /** @var InvalidElementException */
    protected $exception;
    protected $object;
    protected $needType;


    protected function setUp(): void
    {
        $this->object = new Element(1);
        $this->needType = get_class(new class
        {

        });
        $this->exception = new InvalidElementException($this->object, $this->needType);
    }

    public function testGetNeedType()
    {
        $this->assertEquals($this->object, $this->exception->getObject());
    }

    public function testGetObject()
    {
        $this->assertEquals($this->needType, $this->exception->getNeedType());
    }
}
