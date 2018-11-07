<?php

namespace kartavik\Support\Tests\Unit;

use kartavik\Support\Collection;
use kartavik\Support\Strict;
use kartavik\Support\Tests\Mocks\Element;
use PHPUnit\Framework\TestCase;

/**
 * Class CollectionTest
 * @package kartavik\Support\Tests\Unit
 * @coversDefaultClass \kartavik\Support\Collection
 * @internal
 */
class CollectionTest extends TestCase
{
    public function testSuccessMap(): void
    {
        $this->markTestIncomplete('Deprecated test');

        $elements = [
            new Element(1),
            new Element(2),
            new Element(3),
        ];

        /** @noinspection PhpUnhandledExceptionInspection */
        $collections = [
            new Collection(Strict::{Element::class}(), $elements),
            new Collection(Strict::{Element::class}(), $elements),
            new Collection(Strict::{Element::class}(), $elements),
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
     * @expectedException \kartavik\Support\Exception\IncompatibleIterable
     * @expectedExceptionMessage Given iterable object must contain same count elements
     */
    public function testInvalidCountMap(): void
    {
        $this->markTestIncomplete('Deprecated test');

        $first = [
            new Element(1),
            new Element(2),
            new Element(3),
        ];
        $second = [
            new Element(1),
            new Element(2),
        ];
        /** @noinspection PhpUnhandledExceptionInspection */
        $collection = new Collection(Strict::{Element::class}(), $first);

        $collection->map(function (Element $element): int {
            return $element->getValue();
        }, $second);
    }

    /**
     * @expectedException \kartavik\Support\Exception\IncompatibleIterable
     * @expectedExceptionMessage Given iterable object contain invalid element
     */
    public function testInvalidTypeMap(): void
    {
        $this->markTestIncomplete('Deprecated test');

        $first = [
            new Element(1),
            new Element(2),
        ];
        $second = [
            new Element(1),
            new \stdClass(),
        ];
        /** @noinspection PhpUnhandledExceptionInspection */
        $collection = new Collection(Strict::{Element::class}(), $first);

        $collection->map(function (Element $element): int {
            return $element->getValue();
        }, $second);
    }
}
