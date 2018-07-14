<?php

namespace Wearesho\Bobra\Ubki\Tests;

use kartavik\Designer;

use PHPUnit\Framework\TestCase;

use Wearesho\Bobra\Ubki;

/**
 * Class BaseCollectionTest
 * @internal
 * @package Wearesho\Bobra\Ubki\Tests
 */
class BaseCollectionTest extends TestCase
{
    public const INTEGER_227 = 227;
    public const INTEGER_228 = 228;
    public const INTEGER_229 = 229;

    /** @var Designer\Collection */
    protected $collection;

    protected function setUp(): void
    {
        $this->collection = new class extends Designer\Collection
        {
            public function __construct(
                array $elements = [],
                int $flags = 0,
                string $iteratorClass = \ArrayIterator::class
            ) {
                parent::__construct($elements, $this->type(), $flags, $iteratorClass);
            }

            public function type(): string
            {
                return Designer\Tests\Mocks\Element::class;
            }
        };
    }

    public function testInstanceWithInvalidArgument(): void
    {
        $this->expectException(Designer\InvalidElementException::class);

        new $this->collection([
            new class
            {
            }
        ]);
    }

    public function testAppends(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->collection->append(new Designer\Tests\Mocks\Element(BaseCollectionTest::INTEGER_227));
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->collection->append(new Designer\Tests\Mocks\Element(BaseCollectionTest::INTEGER_228));
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->collection->append(new Designer\Tests\Mocks\Element(BaseCollectionTest::INTEGER_229));

        $this->assertEquals($this->collection->offsetGet(0)->getValue(), static::INTEGER_227);
        $this->assertEquals($this->collection->offsetGet(1)->getValue(), static::INTEGER_228);
        $this->assertEquals($this->collection->offsetGet(2)->getValue(), static::INTEGER_229);
    }

    public function testInvalidElement(): void
    {
        $element = new class
        {
        };
        $elementClassName = get_class($element);

        $this->expectException(Designer\InvalidElementException::class);
        $this->expectExceptionMessage(
            "Element {$elementClassName} must be compatible with " . Designer\Tests\Mocks\Element::class
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->collection->append($element);
    }

    public function testJson(): void
    {
        $this->assertEquals((array)$this->collection, $this->collection->jsonSerialize());
    }

    public function testArrayObjectAccess(): void
    {
        $this->assertEquals(0, count($this->collection));

        $this->testAppends();

        $element = new Designer\Tests\Mocks\Element(static::INTEGER_228);
        $this->collection[] = $element;

        $this->assertEquals(4, count($this->collection));
        $this->assertEquals($element, $this->collection->offsetGet(3));
    }

    public function testStatic(): void
    {
        $element = new Designer\Tests\Mocks\Element(static::INTEGER_228);

        /** @var Designer\Collection $collection */
        $collection = Designer\Collection::{Designer\Tests\Mocks\Element::class}($element);

        $this->assertEquals(Designer\Tests\Mocks\Element::class, $collection->type());

        $collection->append(new Designer\Tests\Mocks\Element(static::INTEGER_229));

        $this->assertEquals(static::INTEGER_228, $collection->offsetGet(0)->getValue());
        $this->assertEquals(static::INTEGER_229, $collection->offsetGet(1)->getValue());
    }

    public function testInstance(): void
    {
        $element = new Designer\Tests\Mocks\Element(static::INTEGER_228);

        $instance = new $this->collection([$element]);
        $this->assertEquals($element, $instance[0]);
    }
}
