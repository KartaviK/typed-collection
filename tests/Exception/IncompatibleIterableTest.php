<?php

namespace kartavik\Collections\Tests\Exception;

use kartavik\Collections\Exception\IncompatibleIterable;

use PHPUnit\Framework\TestCase;

/**
 * Class IncompatibleIterableTest
 * @package kartavik\Collections\Tests\Exception
 * @coversDefaultClass IncompatibleIterable
 * @internal
 */
class IncompatibleIterableTest extends TestCase
{
    /** @var IncompatibleIterable */
    protected $fakeIncompatibleIterable;

    protected function setUp(): void
    {
        $this->fakeIncompatibleIterable = new IncompatibleIterable([1, 2, 3]);
    }

    public function testGetArr(): void
    {
        $this->assertEquals(
            [1, 2, 3],
            $this->fakeIncompatibleIterable->getArr()
        );
    }
}
