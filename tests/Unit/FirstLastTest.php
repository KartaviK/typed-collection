<?php

namespace kartavik\Collections\Tests\Unit;

use kartavik\Collections\Collection;
use kartavik\Collections\Tests\Mocks\Element;
use PHPUnit\Framework\TestCase;

/**
 * Class FirstLastTest
 * @package kartavik\Collections\Tests\Unit
 * @covers \kartavik\Collections\Collection::last()
 * @covers \kartavik\Collections\Collection::first()
 * @internal
 */
class FirstLastTest extends TestCase
{
    protected const FIRST_NUMBER = 1;
    protected const SECOND_NUMBER = 2;

    /** @var Collection */
    protected $collection;

    protected function setUp()
    {
        $this->collection = new Collection(Element::class, [
            new Element(static::FIRST_NUMBER),
            new Element(static::SECOND_NUMBER)
        ]);
    }

    public function testFirst(): void
    {
        $this->assertEquals(
            static::FIRST_NUMBER,
            $this->collection->first()->getValue()
        );
    }

    public function testLast(): void
    {
        $this->assertEquals(
            static::SECOND_NUMBER,
            $this->collection->last()->getValue()
        );
    }
}
