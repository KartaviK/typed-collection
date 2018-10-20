<?php

namespace kartavik\Collections\Tests\Exception;

use kartavik\Collections\Exception\UnprocessedType;
use PHPUnit\Framework\TestCase;


/**
 * Class UnprocessedTypeTest
 * @package kartavik\Collections\Tests\Exception
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
