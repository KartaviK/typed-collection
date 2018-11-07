<?php

namespace kartavik\Support\Tests\Unit\Collection;

use kartavik\Support\Strict;
use kartavik\Support\Tests\Extend\CollectionTestCase;

/**
 * Class IntegerTest
 * @package kartavik\Support\Tests\Unit\Collection
 * @coversDefaultClass \kartavik\Support\Collection
 * @internal
 */
class IntegerTest extends CollectionTestCase
{
    protected function setUp(): void
    {
        $this->strict = Strict::integer();
    }

    public function getItem(): int
    {
        return mt_rand();
    }

    public function getSumItems(): array
    {
        return [
            10,
            25,
            100
        ];
    }

    public function getSumClosure(): \Closure
    {
        return function (int $item): int {
            return $item - 5;
        };
    }

    public function getExpectedSum(): int
    {
        return 120;
    }
}
