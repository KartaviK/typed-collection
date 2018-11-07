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

    public function testAvailableTypes(): void
    {
        $this->assertArraySubset(
            [
                Support\Strict::RESOURCE,
                Support\Strict::BOOLEAN,
                Support\Strict::ARRAYABLE,
                Support\Strict::FLOAT,
                Support\Strict::INTEGER,
                Support\Strict::OBJECT,
                Support\Strict::STRING
            ],
            Support\Strict::availableTypes()
        );
    }

    public function testFloat(): void
    {
        $this->assertEquals(Support\Strict::FLOAT, Support\Strict::float()->t());
        $this->assertEquals(Support\Strict::FLOAT, Support\Strict::{Support\Strict::FLOAT}()->t());
    }

    public function testResource(): void
    {
        $this->assertEquals(Support\Strict::RESOURCE, Support\Strict::resource()->t());
        $this->assertEquals(Support\Strict::RESOURCE, Support\Strict::{Support\Strict::RESOURCE}()->t());
    }

    public function testIterable(): void
    {
        $this->assertEquals(Support\Strict::ARRAYABLE, Support\Strict::arrayable()->t());
        $this->assertEquals(Support\Strict::ARRAYABLE, Support\Strict::{Support\Strict::ARRAYABLE}()->t());
    }

    public function testString(): void
    {
        $this->assertEquals(Support\Strict::STRING, Support\Strict::string()->t());
        $this->assertEquals(Support\Strict::STRING, Support\Strict::{Support\Strict::STRING}()->t());
    }

    public function testObject(): void
    {
        $this->assertEquals(Support\Strict::OBJECT, Support\Strict::object()->t());
        $this->assertEquals(\stdClass::class, Support\Strict::object(\stdClass::class)->t());
        $this->assertEquals(Support\Strict::OBJECT, Support\Strict::{Support\Strict::OBJECT}()->t());
        $this->assertEquals(\stdClass::class, Support\Strict::{\stdClass::class}()->t());

        $this->expectException(Support\Exception\UnprocessedType::class);

        Support\Strict::object('invalid class name');
    }

    public function testInteger(): void
    {
        $this->assertEquals(Support\Strict::INTEGER, Support\Strict::integer()->t());
        $this->assertEquals(Support\Strict::INTEGER, Support\Strict::{Support\Strict::INTEGER}()->t());
    }

    public function testBoolean(): void
    {
        $this->assertEquals(Support\Strict::BOOLEAN, Support\Strict::boolean()->t());
        $this->assertEquals(Support\Strict::BOOLEAN, Support\Strict::{Support\Strict::BOOLEAN}()->t());
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
