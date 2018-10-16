<?php

namespace kartavik\Collections\Tests\Unit;

use kartavik\Collections\Collection;
use kartavik\Collections\Tests\Mocks\Element;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayAccessInterfaceTest
 * @package kartavik\Collections\Tests\Unit
 */
class ArrayAccessInterfaceTest extends TestCase
{
    public function testOffsetGetIntegerKeys(): void
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

    public function testOffsetSetStringKeys(): void
    {
        $elements = [new Element(mt_rand())];
        $collection = new Collection(Element::class, $elements);

        $collection->offsetSet('key', $elements[0]);

        $this->assertCount(2, $collection);
        $this->assertEquals($elements[0], $collection->offsetGet(0));
        $this->assertEquals($elements[0], $collection->offsetGet('key'));
    }
}
