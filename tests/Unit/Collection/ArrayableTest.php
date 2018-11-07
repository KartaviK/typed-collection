<?php

namespace kartavik\Support\Tests\Unit\Collection;

use kartavik\Support\Strict;
use kartavik\Support\Tests\Extend\CollectionTestCase;

/**
 * Class ArrayableTest
 * @package kartavik\Support\Tests\Unit\Collection
 * @coversDefaultClass \kartavik\Support\Collection
 * @internal
 */
class ArrayableTest extends CollectionTestCase
{
    protected function setUp(): void
    {
        $this->strict = Strict::arrayable();
    }

    public function getItem(): array
    {
        return [1, 'asd', 123.456, new \stdClass()];
    }

    public function getSumItems(): array
    {
        return [
            $this->getItem(),
            $this->getItem(),
            $this->getItem(),
        ];
    }

    public function getSumClosure(): \Closure
    {
        return function (array $item): int {
            return $item[0];
        };
    }

    public function getExpectedSum(): float
    {
        return 3;
    }
}
