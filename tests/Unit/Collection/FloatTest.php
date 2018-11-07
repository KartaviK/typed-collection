<?php

namespace kartavik\Support\Tests\Unit\Collection;

use kartavik\Support\Strict;
use kartavik\Support\Tests\Extend\CollectionTestCase;

/**
 * Class FloatTest
 * @package kartavik\Support\Tests\Unit\Collection
 * @coversDefaultClass \kartavik\Support\Collection
 * @internal
 */
class FloatTest extends CollectionTestCase
{
    protected function setUp(): void
    {
        $this->strict = Strict::float();
    }

    public function getItem(): float
    {
        return 123.456;
    }

    public function getSumItems(): array
    {
        return [
            1.1,
            1.2,
            1.3
        ];
    }

    public function getSumClosure(): \Closure
    {
        return function (float $item): float {
            return $item * 2;
        };
    }

    public function getExpectedSum(): float
    {
        return 2.2 + 2.4 + 2.6;
    }
}
