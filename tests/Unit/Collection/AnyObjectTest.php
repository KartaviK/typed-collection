<?php

namespace kartavik\Support\Tests\Unit\Collection;

use kartavik\Support\Strict;
use kartavik\Support\Tests\Extend\CollectionTestCase;
use kartavik\Support\Tests\Mocks\Element;

/**
 * Class AnyObjectTest
 * @package kartavik\Support\Tests\Unit\Collection
 * @coversDefaultClass \kartavik\Support\Collection
 * @internal
 */
class AnyObjectTest extends CollectionTestCase
{
    protected function setUp(): void
    {
        $this->strict = Strict::object();
    }

    public function getItem()
    {
        return [
            new \stdClass(),
            new Element(mt_rand())
        ][mt_rand(0, 1)];
    }

    public function getSumItems(): array
    {
        return [
            new Element(10),
            new Element(20),
            new Element(30),
        ];
    }

    public function getSumClosure(): \Closure
    {
        return function (Element $item) {
            return $item->getValue();
        };
    }

    public function getExpectedSum(): int
    {
        return 60;
    }
}
