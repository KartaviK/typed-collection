<?php

namespace kartavik\Support\Tests\Unit;

use kartavik\Support;
use PHPUnit\Framework\TestCase;

/**
 * Class StrictTest
 * @package kartavik\Support\Tests\Unit
 * @coversDefaultClass \kartavik\Support\Strict
 * @internal
 */
class StrictTest extends TestCase
{
    /** @var Support\Strict */
    protected $fakeStrict;

    public function testValidateBoolean(): void
    {
        $strict = Support\Strict::boolean();

        $this->assertTrue(
            $strict->validate((bool)mt_rand())
        );
        $this->assertFalse(
            $strict->validate(mt_rand())
        );
    }

    public function testValidateString(): void
    {
        $strict = Support\Strict::string();

        $this->assertTrue(
            $strict->validate('random_string')
        );
        $this->assertFalse(
            $strict->validate(mt_rand())
        );
    }

    public function testValidateInteger(): void
    {
        $strict =Support\Strict::integer();

        $this->assertTrue(
            $strict->validate(mt_rand())
        );
        $this->assertFalse(
            $strict->validate(true)
        );
    }

    public function testValidateFloat(): void
    {
        $strict = Support\Strict::float();

        $this->assertTrue(
            $strict->validate((float)mt_rand())
        );
        $this->assertFalse(
            $strict->validate(new \stdClass())
        );
    }

    public function testValidateArray(): void
    {
        $strict = Support\Strict::arrayable();

        $this->assertTrue(
            $strict->validate(range(0, 10))
        );
        $this->assertFalse(
            $strict->validate('str')
        );
    }

    public function testValidateAnyObject(): void
    {
        $this->assertTrue(
            Support\Strict::object()
                ->validate(new \stdClass())
        );
        $this->assertTrue(
            Support\Strict::object()
                ->validate(new Support\Tests\Mocks\Element(mt_rand()))
        );
        $this->assertTrue(
            Support\Strict::object()
                ->validate(
                    new class
                    {
                    }
                )
        );
    }

    public function testValidateConcreteObject(): void
    {
        $this->assertTrue(
            Support\Strict::object(\stdClass::class)
                ->validate(new \stdClass())
        );
        $this->assertFalse(
            Support\Strict::object(\stdClass::class)
                ->validate(new Support\Tests\Mocks\Element(mt_rand()))
        );
    }

    public function testFloat(): void
    {
        $this->assertEquals(Support\Strict::FLOAT, Support\Strict::float()->type());
        $this->assertEquals(Support\Strict::FLOAT, Support\Strict::{Support\Strict::FLOAT}()->type());
    }

    public function testResource(): void
    {
        $this->assertEquals(Support\Strict::RESOURCE, Support\Strict::resource()->type());
        $this->assertEquals(Support\Strict::RESOURCE, Support\Strict::{Support\Strict::RESOURCE}()->type());
    }

    public function testIterable(): void
    {
        $this->assertEquals(Support\Strict::ARRAYABLE, Support\Strict::arrayable()->type());
        $this->assertEquals(Support\Strict::ARRAYABLE, Support\Strict::{Support\Strict::ARRAYABLE}()->type());
    }

    public function testString(): void
    {
        $this->assertEquals(Support\Strict::STRING, Support\Strict::string()->type());
        $this->assertEquals(Support\Strict::STRING, Support\Strict::{Support\Strict::STRING}()->type());
    }

    public function testObject(): void
    {
        $this->assertEquals(Support\Strict::OBJECT, Support\Strict::object()->type());
        $this->assertEquals(\stdClass::class, Support\Strict::object(\stdClass::class)->type());
        $this->assertEquals(Support\Strict::OBJECT, Support\Strict::{Support\Strict::OBJECT}()->type());
        $this->assertEquals(\stdClass::class, Support\Strict::{\stdClass::class}()->type());

        $this->expectException(Support\Exception\UnprocessedType::class);

        Support\Strict::object('invalid class name');
    }

    public function testInteger(): void
    {
        $this->assertEquals(Support\Strict::INTEGER, Support\Strict::integer()->type());
        $this->assertEquals(Support\Strict::INTEGER, Support\Strict::{Support\Strict::INTEGER}()->type());
    }

    public function testBoolean(): void
    {
        $this->assertEquals(Support\Strict::BOOLEAN, Support\Strict::boolean()->type());
        $this->assertEquals(Support\Strict::BOOLEAN, Support\Strict::{Support\Strict::BOOLEAN}()->type());
    }

    public function testTypeof(): void
    {
        $this->assertEquals(Support\Strict::string(), Support\Strict::typeof('test_str'));
        $this->assertEquals(Support\Strict::float(), Support\Strict::typeof(123.456));
        $this->assertEquals(Support\Strict::integer(), Support\Strict::typeof(123456));
        $this->assertEquals(Support\Strict::boolean(), Support\Strict::typeof(true));
        $this->assertEquals(Support\Strict::boolean(), Support\Strict::typeof(false));
        $this->assertEquals(Support\Strict::object(\stdClass::class), Support\Strict::typeof(new \stdClass()));
        $this->assertEquals(Support\Strict::arrayable(), Support\Strict::typeof([1, 'asd', 123.456]));
    }
}
