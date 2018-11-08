<?php

namespace kartavik\Support\Tests\Extend;

use kartavik\Support\Collection;
use kartavik\Support\Strict;
use PHPUnit\Framework\TestCase;

/**
 * Class CollectionTestCase
 * @package kartavik\Support\Tests\Extend
 */
class CollectionTestCase extends TestCase
{
    /** @var Strict */
    protected $strict;

    public function createCollection(iterable ...$items): Collection
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return new Collection($this->strict, ...$items);
    }

    public function getItem()
    {
        return null;
    }

    public function getSumItems(): array
    {
        return null;
    }

    public function getSumClosure(): \Closure
    {
        return null;
    }

    public function getExpectedSum()
    {
        return null;
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(Collection::class, $this->createCollection());
    }

    public function testStaticInstance(): void
    {
        $items= [
            [
                $this->getItem(),
                $this->getItem(),
                $this->getItem(),
            ],
            [
                $this->getItem(),
                $this->getItem(),
                $this->getItem(),
            ],
            [
                $this->getItem(),
                $this->getItem(),
                $this->getItem(),
            ]
        ];

        $this->assertInstanceOf(Collection::class, Collection::{$this->strict->type()}());
        $this->assertInstanceOf(Collection::class, Collection::{$this->strict->type()}($items[0]));
        $this->assertInstanceOf(Collection::class, Collection::{$this->strict->type()}($items[1], $items[2]));
        $this->assertInstanceOf(Collection::class, Collection::{$this->strict->type()}(...$items));
    }

    public function testCount(): void
    {
        $items= [
            [
                $this->getItem(),
                $this->getItem(),
                $this->getItem(),
            ],
            [
                $this->getItem(),
                $this->getItem(),
                $this->getItem(),
            ],
            [
                $this->getItem(),
                $this->getItem(),
                $this->getItem(),
            ]
        ];
        $collection = $this->createCollection(...$items);

        $this->assertCount(9, $collection);
    }

    public function testInstanceWithArray(): void
    {
        $items = [
            $this->getItem(),
            $this->getItem(),
            $this->getItem()
        ];
        $collection = $this->createCollection($items);

        $this->assertCount(3, $collection);
    }

    public function testInstanceWithUnboundedArrays(): void
    {
        $arrs= [
            [
                $this->getItem(),
                $this->getItem(),
                $this->getItem(),
            ],
            [
                $this->getItem(),
                $this->getItem(),
                $this->getItem(),
            ],
            [
                $this->getItem(),
                $this->getItem(),
                $this->getItem(),
            ]
        ];
        $collection = $this->createCollection(...$arrs);

        $this->assertCount(9, $collection);
    }

    public function testInstanceWithCollection(): void
    {
        $arrayObject = new \ArrayObject([
            $this->getItem(),
            $this->getItem(),
            $this->getItem(),
        ]);
        $collection = $this->createCollection($arrayObject);

        $this->assertCount(3, $collection);
    }

    public function testInstanceWithUnboundedCollections(): void
    {
        $arrs = [
            new \ArrayObject([
                $this->getItem(),
                $this->getItem(),
                $this->getItem(),
            ]),
            new \ArrayObject([
                $this->getItem(),
                $this->getItem(),
                $this->getItem(),
            ]),
            new \ArrayObject([
                $this->getItem(),
                $this->getItem(),
                $this->getItem(),
            ])
        ];
        $collection = $this->createCollection(...$arrs);

        $this->assertCount(9, $collection);
    }

    /**
     * @expectedException \kartavik\Support\Exception\Validation
     */
    public function testFailureInstance(): void
    {
        $items = [
            $this->getItem(),
            $this->getItem(),
            null,
        ];
        $this->createCollection($items);
    }

    /**
     * @expectedException \kartavik\Support\Exception\UnprocessedType
     * @expectedExceptionMessage Given type is: SomeInvalidType
     */
    public function testInvalidType(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        new Collection(Strict::object('SomeInvalidType'));
    }

    public function testAppend(): void
    {
        $collection = $this->createCollection();

        for ($i = 0; $i < 3; $i++) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $collection->append($this->getItem());
        }
        /** @noinspection PhpUnhandledExceptionInspection */
        $collection->append(...[$this->getItem(), $this->getItem(), $this->getItem()]);

        $this->assertCount(6, $collection);
    }

    public function testPop(): void
    {
        $last = $this->getItem();
        $items = [
            $this->getItem(),
            $this->getItem(),
            $last
        ];
        $collection = $this->createCollection($items);

        $this->assertCount(3, $collection);

        $popped = $collection->pop();

        $this->assertEquals($last, $popped);
        $this->assertCount(2, $collection);
    }

    public function testReverse(): void
    {
        $items = [
            'first' => $this->getItem(),
            'second' => $this->getItem(),
            'third' => $this->getItem(),
            0 => $this->getItem(),
            1 => $this->getItem(),
            2 => $this->getItem(),
        ];
        $collection = $this->createCollection();

        foreach ($items as $index => $item) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $collection->add($item, $index);
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertArraySubset(array_reverse($items), $collection->reverse());
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertArraySubset(array_reverse($items, true), $collection->reverse(true));
    }

    public function testFirst(): void
    {
        $first = $this->getItem();
        $items = [
            $first,
            $this->getItem(),
            $this->getItem(),
        ];
        $collection = $this->createCollection($items);

        $this->assertEquals($first, $collection->first());
    }

    public function testLast(): void
    {
        $last = $this->getItem();
        $items = [
            $this->getItem(),
            $this->getItem(),
            $last
        ];
        $collection = $this->createCollection($items);

        $this->assertEquals($last, $collection->last());
    }

    public function testSum(): void
    {
        $this->assertEquals(
            $this->getExpectedSum(),
            $this->createCollection($this->getSumItems())
                ->sum($this->getSumClosure())
        );
    }

    public function testIsCompatible(): void
    {
        $items = [
            $this->getItem(),
            $this->getItem(),
            $this->getItem(),
        ];
        $incompatibleItems = [
            $this->getItem(),
            $this->getItem(),
            $this->getItem(),
            null,
        ];
        $collection = $this->createCollection($items);
        $compatibleCollection = $this->createCollection($items);

        $this->assertTrue($collection->isCompatible($compatibleCollection));
        $this->assertFalse($collection->isCompatible($incompatibleItems));
    }

    public function testJsonSerialize(): void
    {
        $items = [
            $this->getItem(),
            $this->getItem(),
            $this->getItem(),
            $this->getItem(),
            $this->getItem(),
            $this->getItem(),
        ];
        $collection = $this->createCollection($items);

        $this->assertArraySubset($items, $collection->jsonSerialize());
    }

    public function testOffset(): void
    {
        $items = [
            $this->getItem(),
            $this->getItem(),
            $this->getItem(),
        ];
        $key = 'key';
        $item = $this->getItem();
        $collection = $this->createCollection($items);
        $collection[$key] = $item;

        $this->assertArrayHasKey($key, $collection);
        $this->assertEquals($item, $collection[$key]);
        $this->assertTrue(isset($collection[$key]));

        unset($collection[$key]);

        $this->assertArrayNotHasKey($key, $collection);
    }

    public function testColumn(): void
    {
        $items = [
            [
                $this->getItem(),
                $this->getItem(),
                $this->getItem(),
            ],
            [
                $this->getItem(),
                $this->getItem(),
                $this->getItem(),
            ],
            [
                $this->getItem(),
                $this->getItem(),
                $this->getItem(),
            ],
        ];
        $collection = $this->createCollection(...$items);
        $fetchTypeCallback = function ($item) {
            return Strict::strictof($item);
        };
        /** @noinspection PhpUnhandledExceptionInspection */
        $strictCollection = $collection->map($fetchTypeCallback);

        $this->assertArraySubset(
            array_map($fetchTypeCallback, array_merge(...$items)),
            $strictCollection
        );
    }

    public function testChunk(): void
    {
        $items = [
            $this->getItem(),
            $this->getItem(),
            $this->getItem(),
        ];
        $collection = $this->createCollection($items);
        $chunked = $collection->chunk(2);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertArraySubset(
            Collection::{Collection::class}([
                $this->createCollection([$items[0], $items[1],]),
                $this->createCollection([$items[2],]),
            ]),
            $chunked
        );
    }
}
