<?php

namespace kartavik\Support\Tests\Unit\Collection;

use kartavik\Support\Strict;
use kartavik\Support\Tests\Extend\CollectionTestCase;

/**
 * Class BooleanTest
 * @package kartavik\Support\Tests\Unit\Collection
 * @coversDefaultClass \kartavik\Support\Collection
 * @internal
 */
class BooleanTest extends CollectionTestCase
{
    protected function setUp(): void
    {
        $this->strict = Strict::boolean();
    }

    public function getItem(): bool
    {
        return [
            true,
            false,
        ][mt_rand(0, 1)];
    }

    public function getSumItems(): array
    {
        return [
            true,
            true,
            true,
        ];
    }

    public function getSumClosure(): \Closure
    {
        return function (float $item): int {
            return (int)$item;
        };
    }

    public function getExpectedSum(): int
    {
        return 3;
    }
}
