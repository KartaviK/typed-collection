<?php

namespace kartavik\Support\Tests\Exception;

use PHPUnit\Framework\TestCase;
use kartavik\Support\Exception\Validation;

/**
 * Class ValidationTest
 * @package kartavik\Support\Tests\Exception
 * @coversDefaultClass Validation
 * @internal
 */
class ValidationTest extends TestCase
{
    protected const VAR = 'var';

    /** @var Validation */
    protected $fakeValidation;

    protected function setUp(): void
    {
        $this->fakeValidation = new Validation(static::VAR);
    }

    public function testGetVar(): void
    {
        $this->assertEquals(static::VAR, $this->fakeValidation->getVar());
    }
}
