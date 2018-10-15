<?php

namespace kartavik\Collections\Tests;

use kartavik\Collections;

use PHPUnit\Framework\TestCase;

/**
 * Class BaseCollectionTest
 * @internal
 * @package kartavik\Designer\Tests
 */
class BaseCollectionTest extends TestCase
{
    public const FIRST_TEST_NUMBER = 227;
    public const SECOND_TEST_NUMBER = 228;
    public const THIRD_TEST_NUMBER = 229;

    /** @var Collections\Collection */
    protected $collection;

    protected function setUp(): void
    {
        $this->collection = new class extends Collections\Collection
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
                return Mocks\Element::class;
            }
        };
    }

    public function testInstanceWithInvalidArgument(): void
    {
        $this->expectException(Collections\Exceptions\InvalidElementException::class);

        new $this->collection([
            new class
            {
            }
        ]);
    }

    public function testAppends(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->collection->append(new Mocks\Element(static::FIRST_TEST_NUMBER));
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->collection->append(new Mocks\Element(static::SECOND_TEST_NUMBER));
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->collection->append(new Mocks\Element(static::THIRD_TEST_NUMBER));

        $this->assertEquals($this->collection->offsetGet(0)->getValue(), static::FIRST_TEST_NUMBER);
        $this->assertEquals($this->collection->offsetGet(1)->getValue(), static::SECOND_TEST_NUMBER);
        $this->assertEquals($this->collection->offsetGet(2)->getValue(), static::THIRD_TEST_NUMBER);
    }

    public function testInvalidElement(): void
    {
        $element = new class
        {
        };
        $elementClassName = get_class($element);

        $this->expectException(Collections\Exceptions\InvalidElementException::class);
        $this->expectExceptionMessage(
            "Element {$elementClassName} must be instance of " . Mocks\Element::class
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

        $element = new Collections\Tests\Mocks\Element(static::SECOND_TEST_NUMBER);
        $this->collection[] = $element;

        $this->assertEquals(4, count($this->collection));
        $this->assertEquals($element, $this->collection->offsetGet(3));
    }

    public function testStatic(): void
    {
        $element = new Collections\Tests\Mocks\Element(static::SECOND_TEST_NUMBER);

        /** @var Collections\Collection $collection */
        $collection = Collections\Collection::{Mocks\Element::class}($element);

        $this->assertEquals(Mocks\Element::class, $collection->type());

        $collection->append(new Mocks\Element(static::THIRD_TEST_NUMBER));

        $this->assertEquals(static::SECOND_TEST_NUMBER, $collection->offsetGet(0)->getValue());
        $this->assertEquals(static::THIRD_TEST_NUMBER, $collection->offsetGet(1)->getValue());

        $collection = Collections\Collection::{Mocks\Element::class}([$element, $element]);
        $this->assertEquals($element, $collection->offsetGet(0));
        $this->assertEquals($element, $collection->offsetGet(1));

        $this->expectException(\BadMethodCallException::class);
        $invalidType = 'asd asd';
        Collections\Collection::{$invalidType}();
    }

    public function testTypeOfCollection(): void
    {
        /** @var Collections\Collection $collection */
        $collection = Collections\Collection::{\Exception::class}();

        $this->assertEquals(\Exception::class, $collection->type());
        $this->assertEquals(Collections\Collection::class, get_class($collection));
    }

    public function testInstance(): void
    {
        $element = new Mocks\Element(static::SECOND_TEST_NUMBER);

        $instance = new $this->collection([$element]);
        $this->assertEquals($element, $instance[0]);
    }

    public function testMap(): void
    {
        $firstSubElement = new Mocks\SubElement(static::FIRST_TEST_NUMBER);
        $secondSubElement = new Mocks\SubElement(static::SECOND_TEST_NUMBER);

        /** @var Collections\Collection $collection */
        $collection = Collections\Collection::{Mocks\Element::class}([
            new Mocks\Element(static::FIRST_TEST_NUMBER, $firstSubElement),
            new Mocks\Element(static::SECOND_TEST_NUMBER, $secondSubElement)
        ]);

        $mapped = $collection->map(function (Mocks\Element $element): Mocks\SubElement {
            return $element->getSubElement();
        });

        $this->assertEquals($firstSubElement, $mapped->offsetGet(0));
        $this->assertEquals($secondSubElement, $mapped->offsetGet(1));
    }

    public function testChunk(): void
    {
        $elements = [
            new Mocks\Element(static::FIRST_TEST_NUMBER),
            new Mocks\Element(static::FIRST_TEST_NUMBER),
            new Mocks\Element(static::SECOND_TEST_NUMBER),
            new Mocks\Element(static::SECOND_TEST_NUMBER),
            new Mocks\Element(static::THIRD_TEST_NUMBER),
            new Mocks\Element(static::THIRD_TEST_NUMBER)
        ];

        /** @var Collections\Collection $collection */
        $collection = Collections\Collection::{Mocks\Element::class}($elements);

        $chunked = $collection->chunk(2);

        $this->assertEquals($elements[0], $chunked[0][0]);
        $this->assertEquals($elements[1], $chunked[0][1]);
        $this->assertEquals($elements[2], $chunked[1][0]);
        $this->assertEquals($elements[3], $chunked[1][1]);
        $this->assertEquals($elements[4], $chunked[2][0]);
        $this->assertEquals($elements[5], $chunked[2][1]);
    }

    public function testColumn(): void
    {
        $elements = [
            new Mocks\Element(
                static::FIRST_TEST_NUMBER,
                new Mocks\SubElement(static::THIRD_TEST_NUMBER)
            ),
            new Mocks\Element(
                static::FIRST_TEST_NUMBER,
                new Mocks\SubElement(static::THIRD_TEST_NUMBER)
            ),
            new Mocks\Element(
                static::SECOND_TEST_NUMBER,
                new Mocks\SubElement(static::SECOND_TEST_NUMBER)
            ),
            new Mocks\Element(
                static::SECOND_TEST_NUMBER,
                new Mocks\SubElement(static::SECOND_TEST_NUMBER)
            ),
            new Mocks\Element(
                static::THIRD_TEST_NUMBER,
                new Mocks\SubElement(static::FIRST_TEST_NUMBER)
            ),
            new Mocks\Element(
                static::THIRD_TEST_NUMBER,
                new Mocks\SubElement(static::FIRST_TEST_NUMBER)
            )
        ];

        /** @var Collections\Collection $collection */
        $collection = Collections\Collection::{Mocks\Element::class}($elements);

        $column = $collection->column('getSubElement');

        $this->assertEquals($elements[0]->getSubElement(), $column[0]);
        $this->assertEquals($elements[1]->getSubElement(), $column[1]);
        $this->assertEquals($elements[2]->getSubElement(), $column[2]);
        $this->assertEquals($elements[3]->getSubElement(), $column[3]);
        $this->assertEquals($elements[4]->getSubElement(), $column[4]);
        $this->assertEquals($elements[5]->getSubElement(), $column[5]);

        $column = $collection->column('getSubElement', function (Mocks\SubElement $item): Mocks\SubElement {
            return new Mocks\SubElement($item->getValue() + 1);
        });

        $this->assertEquals(static::THIRD_TEST_NUMBER + 1, $column[0]->getValue());
        $this->assertEquals(static::THIRD_TEST_NUMBER + 1, $column[1]->getValue());
        $this->assertEquals(static::SECOND_TEST_NUMBER + 1, $column[2]->getValue());
        $this->assertEquals(static::SECOND_TEST_NUMBER + 1, $column[3]->getValue());
        $this->assertEquals(static::FIRST_TEST_NUMBER + 1, $column[4]->getValue());
        $this->assertEquals(static::FIRST_TEST_NUMBER + 1, $column[5]->getValue());
    }

    public function testPop(): void
    {
        $elementToPop = new Mocks\Element(static::THIRD_TEST_NUMBER);

        $elements = [
            new Mocks\Element(static::FIRST_TEST_NUMBER),
            new Mocks\Element(static::FIRST_TEST_NUMBER),
            new Mocks\Element(static::SECOND_TEST_NUMBER),
            new Mocks\Element(static::SECOND_TEST_NUMBER),
            new Mocks\Element(static::THIRD_TEST_NUMBER),
            $elementToPop
        ];

        /** @var Collections\Collection $collection */
        $collection = Collections\Collection::{Mocks\Element::class}($elements);

        $this->assertEquals(count($elements), $collection->count());

        $popped = $collection->pop();

        $this->assertEquals($elementToPop, $popped);
        $this->assertEquals(count($elements) - 1, $collection->count());
    }

    public function testSum(): void
    {
        $expectedSum = (static::FIRST_TEST_NUMBER * 2)
            + (static::SECOND_TEST_NUMBER * 2)
            + (static::THIRD_TEST_NUMBER * 2);

        $elements = [
            new Mocks\Element(static::FIRST_TEST_NUMBER),
            new Mocks\Element(static::FIRST_TEST_NUMBER),
            new Mocks\Element(static::SECOND_TEST_NUMBER),
            new Mocks\Element(static::SECOND_TEST_NUMBER),
            new Mocks\Element(static::THIRD_TEST_NUMBER),
            new Mocks\Element(static::THIRD_TEST_NUMBER)
        ];

        /** @var Collections\Collection $collection */
        $collection = Collections\Collection::{Mocks\Element::class}($elements);

        $sum = $collection->sum(function (Mocks\Element $item) {
            return $item->getValue();
        });

        $this->assertEquals($expectedSum, $sum);
    }
}
