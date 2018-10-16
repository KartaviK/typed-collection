<?php

namespace kartavik\Collections\Tests\Unit;

use kartavik\Collections\Collection;
use kartavik\Collections\Tests\Mocks\Element;
use PHPUnit\Framework\TestCase;

/**
 * Class SerializeTest
 * @package kartavik\Collections\Tests\Unit
 */
class SerializeTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $elements = [
            new Element(mt_rand()),
            new Element(mt_rand()),
        ];
        $collection = new Collection(Element::class, $elements);

        $this->assertArraySubset($elements, $collection->jsonSerialize());
        $this->assertEquals($elements, $collection->jsonSerialize());
    }

    public function testSerializeInterface(): void
    {
        $elements = [new Element(0),];
        $collection = new Collection(Element::class, $elements);
        $serialized = $collection->serialize();

        $collection = new Collection(Element::class);
        $collection->unserialize($serialized);

        $this->assertEquals($serialized, $collection->serialize());
        $this->assertCount(1, $collection);
        $this->assertEquals($elements[0], $collection->offsetGet(0));
    }
}
