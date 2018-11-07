<?php

namespace kartavik\Support\Tests\Unit\Collection;

use kartavik\Support\Strict;
use kartavik\Support\Tests\Extend\CollectionTestCase;

/**
 * Class StringTest
 * @package kartavik\Support\Tests\Unit\Collection
 * @coversDefaultClass \kartavik\Support\Collection
 * @internal
 */
class StringTest extends CollectionTestCase
{
    protected function setUp(): void
    {
        $this->strict = Strict::string();
    }

    public function getItem(): string
    {
        return 'test_string';
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
        return function (string $item): int {
            return mb_strlen($item);
        };
    }

    public function getExpectedSum(): int
    {
        return 33;
    }
}
