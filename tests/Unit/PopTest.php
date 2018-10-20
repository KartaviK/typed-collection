<?php

namespace kartavik\Collections\Tests\Unit;

use kartavik\Collections\Collection;
use PHPUnit\Framework\TestCase;

/**
 * Class PopTest
 * @package kartavik\Collections\Tests\Unit
 */
class PopTest extends TestCase
{
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
}
