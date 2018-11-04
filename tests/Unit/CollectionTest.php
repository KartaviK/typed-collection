<?php

namespace kartavik\Collections\Tests\Unit;

use kartavik\Collections\Collection;
use kartavik\Collections\Exception\IncompatibleIterable;
use kartavik\Collections\Exception\InvalidElement;
use kartavik\Collections\Exception\UnprocessedType;
use kartavik\Collections\Tests\Mocks\Element;
use PHPUnit\Framework\TestCase;

/**
 * Class CollectionTest
 * @package kartavik\Collections\Tests\Unit
 * @coversDefaultClass \kartavik\Collections\Collection
 * @internal
 */
class CollectionTest extends TestCase
{
    public function testSimpleConstruct(): void
    {
        $collection = new Collection(Element::class);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEmpty($collection);
        $this->assertEquals(new Collection(Element::class), $collection);
        $this->assertEquals(Element::class, $collection->type());
    }

    public function testConstructWithArray(): void
    {
        $elements = [
            new Element(mt_rand()),
            new Element(mt_rand()),
        ];
        $collection = new Collection(Element::class, $elements);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(2, $collection);
        $this->assertNotEquals(new Collection(Element::class), $collection);
        $this->assertEquals(new Collection(Element::class, $elements), $collection);
        $this->assertEquals(Element::class, $collection->type());
    }

    public function testConstructWithCollection(): void
    {
        $elements = [
            new Element(mt_rand()),
            new Element(mt_rand()),
        ];
        $subCollection = new Collection(Element::class, $elements);
        $collection = new Collection(Element::class, $subCollection);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(2, $collection);
        $this->assertNotEquals(new Collection(Element::class), $collection);
        $this->assertEquals($subCollection, $collection);
        $this->assertEquals(Element::class, $collection->type());
    }

    public function testStaticConstruct(): void
    {
        $collection = Collection::{Element::class}();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEmpty($collection);
        $this->assertEquals(new Collection(Element::class), $collection);
        $this->assertEquals(Element::class, $collection->type());
    }

    public function testStaticConstructWithArray(): void
    {
        $elements = [
            new Element(mt_rand()),
            new Element(mt_rand()),
        ];
        $subCollection = new Collection(Element::class, $elements);
        $collection = Collection::{Element::class}($elements);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(2, $collection);
        $this->assertNotEquals(new Collection(Element::class), $collection);
        $this->assertEquals($subCollection, $collection);
        $this->assertEquals(Element::class, $collection->type());
    }

    public function testStaticConstructWithCollection(): void
    {
        $elements = [
            new Element(mt_rand()),
            new Element(mt_rand()),
        ];
        $subCollection = new Collection(Element::class, $elements);
        $collection = Collection::{Element::class}($subCollection);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(2, $collection);
        $this->assertNotEquals(new Collection(Element::class), $collection);
        $this->assertEquals($subCollection, $collection);
        $this->assertEquals(Element::class, $collection->type());
    }

    public function testConstructWithInvalidType(): void
    {
        $this->expectException(UnprocessedType::class);

        new Collection('Invalid type');
    }

    public function testStaticConstructWithInvalidType(): void
    {
        $this->expectException(UnprocessedType::class);

        Collection::{'Invalid type'}();
    }

    public function testOffsetGet(): void
    {
        $elements = [
            new Element(mt_rand()),
            new Element(mt_rand()),
        ];
        $collection = new Collection(Element::class, $elements);

        $this->assertCount(2, $collection);
        $this->assertEquals($elements[0], $collection->offsetGet(0));
        $this->assertEquals($elements[1], $collection->offsetGet(1));
    }

    public function testOffsetSet(): void
    {
        $elements = [new Element(mt_rand())];
        $collection = new Collection(Element::class, $elements);

        $collection->offsetSet('key', $elements[0]);

        $this->assertCount(2, $collection);
        $this->assertEquals($elements[0], $collection->offsetGet(0));
        $this->assertEquals($elements[0], $collection->offsetGet('key'));
    }

    public function testOffsetExists(): void
    {
        $elements = [new Element(mt_rand())];
        $collection = new Collection(Element::class, $elements);

        $collection->offsetSet('key', $elements[0]);

        $this->assertTrue($collection->offsetExists('key'));
        $this->assertFalse($collection->offsetExists(1));
    }

    public function testOffsetUnset(): void
    {
        $elements = [new Element(mt_rand())];
        $collection = new Collection(Element::class, $elements);

        $collection->offsetSet('key', $elements[0]);

        $this->assertTrue($collection->offsetExists('key'));

        $collection->offsetUnset('key');

        $this->assertFalse($collection->offsetExists('key'));
    }

    public function testGetIterator(): void
    {
        $elements = [new Element(mt_rand())];
        $collection = new Collection(Element::class, $elements);

        $iterator = $collection->getIterator();

        $this->assertInstanceOf(\ArrayIterator::class, $iterator);
    }

    public function testFirst(): void
    {
        $collection = new Collection(Element::class, [
            new Element(1),
            new Element(2)
        ]);

        $this->assertEquals(1, $collection->first()->getValue());
    }

    public function testLast(): void
    {
        $collection = new Collection(Element::class, [
            new Element(1),
            new Element(2)
        ]);

        $this->assertEquals(2, $collection->last()->getValue());
    }

    public function testSuccessAppend(): void
    {
        $elements = [
            new Element(mt_rand()),
            new Element(mt_rand()),
        ];
        $collection = new Collection(Element::class);

        $collection->append($elements[0]);

        $this->assertInstanceOf(Element::class, $collection->first());

        $collection->append(...$elements);

        $this->assertInstanceOf(Element::class, $collection[1]);
        $this->assertInstanceOf(Element::class, $collection[2]);
    }

    /**
     * @expectedException \kartavik\Collections\Exception\InvalidElement
     */
    public function testFailedAppend(): void
    {
        $elements = [
            new Element(mt_rand()),
            new \stdClass(),
        ];
        $collection = new Collection(Element::class);

        $collection->append(...$elements);
    }

    public function testPop(): void
    {
        $collection = new Collection(\stdClass::class);

        $collection->append(
            new \stdClass(),
            new \stdClass(),
            new \stdClass()
        );

        $this->assertCount(3, $collection);

        $poped = $collection->pop();

        $this->assertEquals($poped, new \stdClass());
        $this->assertCount(2, $collection);
    }

    public function testColumn(): void
    {
        $elements = [];

        for ($i = 0; $i < 5; $i++) {
            $obj = new \stdClass();
            $obj->value = new Element($i);
            $elements[] = $obj;
        }

        $collection = new Collection(\stdClass::class, $elements);

        $this->assertEquals(
            new Collection(Element::class, [
                new Element(0),
                new Element(1),
                new Element(2),
                new Element(3),
                new Element(4),
            ]),
            $collection->column(function (\stdClass $obj): Element {
                return $obj->value;
            })
        );
    }

    public function testCount(): void
    {
        $collection = new Collection(\stdClass::class);

        $collection->append(
            new \stdClass(),
            new \stdClass(),
            new \stdClass()
        );

        $this->assertCount(3, $collection);
        $this->assertEquals(3, $collection->count());
    }

    public function testChunk(): void
    {
        $elements = [
            new \stdClass(),
            new \stdClass(),
            new \stdClass(),
            new \stdClass(),
            new \stdClass(),
        ];

        $collection = new Collection(\stdClass::class, $elements);
        $this->assertEquals(
            new Collection(Collection::class, [
                new Collection(\stdClass::class, [
                    new \stdClass(),
                    new \stdClass(),
                ]),
                new Collection(\stdClass::class, [
                    new \stdClass(),
                    new \stdClass(),
                ]),
                new Collection(\stdClass::class, [
                    new \stdClass(),
                ]),
            ]),
            $collection->chunk(2)
        );
    }

    public function testSum(): void
    {
        $elements = [
            new Element(1),
            new Element(2),
            new Element(3),
        ];

        $collection = new Collection(Element::class, $elements);
        $expectedValue = 6;
        $actualValue = $collection->sum(function (Element $element): int {
            return $element->getValue();
        });

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function testSuccessIsCompatible(): void
    {
        $array = [
            new \stdClass(),
            new \stdClass(),
            new \stdClass(),
        ];
        $arrayObject = new \ArrayObject([
            new \stdClass(),
            new \stdClass(),
            new \stdClass(),
        ]);
        $arrayCollection = new Collection(\stdClass::class, [
            new \stdClass(),
            new \stdClass(),
            new \stdClass(),
        ]);

        $collection = new Collection(\stdClass::class);

        $this->assertTrue($collection->isCompatible($array));
        $this->assertTrue($collection->isCompatible($arrayObject));
        $this->assertTrue($collection->isCompatible($arrayCollection));
    }

    public function testFailedIsCompatible(): void
    {
        $array = [
            new \stdClass(),
            new \stdClass(),
            new Element(1)
        ];
        $arrayObject = new \ArrayObject([
            new \stdClass(),
            new \stdClass(),
            new Element(1)
        ]);
        $arrayCollection = new Collection(Element::class, [
            new Element(1),
            new Element(1),
            new Element(1)
        ]);

        $collection = new Collection(\stdClass::class);

        $this->assertFalse($collection->isCompatible($array));
        $this->assertFalse($collection->isCompatible($arrayObject));
        $this->assertFalse($collection->isCompatible($arrayCollection));
    }

    public function testJsonSerialize(): void
    {
        $array = [
            new \stdClass(),
            new \stdClass(),
            new \stdClass(),
        ];

        $collection = new Collection(\stdClass::class, $array);

        $this->assertArraySubset(
            [
                'type' => \stdClass::class,
                'container' => $array,
            ],
            $collection->jsonSerialize()
        );
    }

    public function testSuccessMap(): void
    {
        $elements = [
            new Element(1),
            new Element(2),
            new Element(3),
        ];

        $collections = [
            new Collection(Element::class, $elements),
            new Collection(Element::class, $elements),
            new Collection(Element::class, $elements),
        ];

        $mapped = $collections[0]->map(function (Element $first, Element $second, Element $third): array {
            return [
                'first' => $first->getValue(),
                'second' => $second->getValue(),
                'third' => $third->getValue(),
            ];
        }, $collections[1], $collections[2]);

        $this->assertEquals(
            [
                [
                    'first' => 1,
                    'second' => 1,
                    'third' => 1,
                ],
                [
                    'first' => 2,
                    'second' => 2,
                    'third' => 2,
                ],
                [
                    'first' => 3,
                    'second' => 3,
                    'third' => 3,
                ],
            ],
            $mapped
        );
    }

    /**
     * @expectedException \kartavik\Collections\Exception\IncompatibleIterable
     * @expectedExceptionMessage Given iterable object must contain same count elements
     */
    public function testInvalidCountMap(): void
    {
        $first = [
            new Element(1),
            new Element(2),
            new Element(3),
        ];
        $second = [
            new Element(1),
            new Element(2),
        ];
        $collection = new Collection(Element::class, $first);

        $collection->map(function (Element $element): int {
            return $element->getValue();
        }, $second);
    }

    /**
     * @expectedException \kartavik\Collections\Exception\IncompatibleIterable
     * @expectedExceptionMessage Given iterable object contain invalid element
     */
    public function testInvalidTypeMap(): void
    {
        $first = [
            new Element(1),
            new Element(2),
        ];
        $second = [
            new Element(1),
            new \stdClass(),
        ];
        $collection = new Collection(Element::class, $first);

        $collection->map(function (Element $element): int {
            return $element->getValue();
        }, $second);
    }
}
