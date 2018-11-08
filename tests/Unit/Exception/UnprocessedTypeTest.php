<?php

namespace kartavik\Support\Tests\Unit\Exception;

use kartavik\Support\Exception\UnprocessedType;
use PHPUnit\Framework\TestCase;

/**
 * Class UnprocessedTypeTest
 * @package kartavik\Support\Tests\Unit\Exception
 * @coversDefaultClass UnprocessedType
 * @internal
 */
class UnprocessedTypeTest extends TestCase
{
    /** @var UnprocessedType */
    protected $fakeUnprocessedType;

    protected function setUp(): void
    {
        $this->fakeUnprocessedType = new UnprocessedType(\stdClass::class);
    }

    public function testGetType(): void
    {
        $this->assertEquals(\stdClass::class, $this->fakeUnprocessedType->getType());
    }
}
