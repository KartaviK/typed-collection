<?php

namespace kartavik\Support\Tests\Unit\Collection;

use kartavik\Support\Strict;
use kartavik\Support\Tests\Extend\CollectionTestCase;

/**
 * Class ConcreteObjectTest
 * @package kartavik\Support\Tests\Unit\Collection
 * @coversDefaultClass \kartavik\Support\Collection
 * @internal
 */
class ConcreteObjectTest extends CollectionTestCase
{
    protected function setUp(): void
    {
        $this->strict = Strict::object(\stdClass::class);
    }

    public function getItem(): \stdClass
    {
        return new \stdClass();
    }

    public function getSumItems(): array
    {
        return array_map(function (\stdClass $std): \stdClass {
            $std->value = 15;

            return $std;
        }, [$this->getItem(), $this->getItem(), $this->getItem(),]);
    }

    public function getSumClosure(): \Closure
    {
        return function (\stdClass $item): int {
            return $item->value;
        };
    }

    public function getExpectedSum(): int
    {
        return 45;
    }
}
