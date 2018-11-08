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
        /** @noinspection PhpUnhandledExceptionInspection */
        Support\Strict::boolean()->validate((bool)mt_rand());

        $this->assertTrue(true);
    }

    public function testValidateString(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        Support\Strict::string()->validate((string)'random_string');
        /** @noinspection PhpUnhandledExceptionInspection */
        Support\Strict::string()->validate((string)''); // even empty string

        $this->assertTrue(true);
    }

    public function testValidateInteger(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        Support\Strict::integer()->validate((int)mt_rand());

        $this->assertTrue(true);
    }

    public function testValidateFloat(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        Support\Strict::float()->validate((float)mt_rand());

        $this->assertTrue(true);
    }

    public function testValidateArray(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        Support\Strict::arrayable()->validate([(int)mt_rand(), (string)'test_string', (float)mt_rand()]);

        $this->assertTrue(true);
    }

    public function testValidateAnyObject(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        Support\Strict::object()->validate(new \stdClass());
        /** @noinspection PhpUnhandledExceptionInspection */
        Support\Strict::object()->validate(new Support\Tests\Mocks\Element(mt_rand()));
        /** @noinspection PhpUnhandledExceptionInspection */
        Support\Strict::object()->validate(new class
        {
        });

        $this->assertTrue(true);
    }

    public function testValidateConcreteObject(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        Support\Strict::object(\stdClass::class)->validate(new \stdClass());

        $this->expectException(Support\Exception\Validation::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        Support\Strict::object(\stdClass::class)->validate(new Support\Tests\Mocks\Element(mt_rand()));
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
        $this->assertEquals(Support\Strict::string(), Support\Strict::strictof('test_str'));
        $this->assertEquals(Support\Strict::float(), Support\Strict::strictof(123.456));
        $this->assertEquals(Support\Strict::integer(), Support\Strict::strictof(123456));
        $this->assertEquals(Support\Strict::boolean(), Support\Strict::strictof(true));
        $this->assertEquals(Support\Strict::boolean(), Support\Strict::strictof(false));
        $this->assertEquals(Support\Strict::object(\stdClass::class), Support\Strict::strictof(new \stdClass()));
        $this->assertEquals(Support\Strict::arrayable(), Support\Strict::strictof([1, 'asd', 123.456]));
    }
}
