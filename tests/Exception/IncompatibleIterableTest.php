<?php

namespace kartavik\Support\Tests\Exception;

use kartavik\Support\Exception\IncompatibleIterable;

use PHPUnit\Framework\TestCase;

/**
 * Class IncompatibleIterableTest
 * @package kartavik\Support\Tests\Exception
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
