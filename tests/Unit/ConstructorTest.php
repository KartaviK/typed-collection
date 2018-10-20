<?php

namespace kartavik\Collections\Tests\Unit;

use kartavik\Collections\Collection;
use kartavik\Collections\Exceptions\UnprocessedTypeException;
use kartavik\Collections\Tests\Mocks\Element;
use PHPUnit\Framework\TestCase;

/**
 * Class ConstructorTest
 * @package kartavik\Collections\Tests\Unit
 */
class ConstructorTest extends TestCase
{
    // region Success constructs

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

    // endregion

    // region Failed constructs

    public function testConstructWithInvalidType(): void
    {
        $this->expectException(UnprocessedTypeException::class);

        new Collection('Invalid type');
    }

    public function testStaticConstructWithInvalidType(): void
    {
        $this->expectException(UnprocessedTypeException::class);

        Collection::{'Invalid type'}();
    }

    // endregion
}
