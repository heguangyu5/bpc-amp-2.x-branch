<?php

namespace Amp\Test\Loop;

use Amp\Loop;
use Amp\Loop\Driver;

class DriverStateTest extends \PHPUnit_Framework_TestCase
{
    /** @var Driver */
    private $loop;

    protected function setUp(): void
    {
        $this->loop = $this->getMockForAbstractClass(Driver::class);
    }

    public function testDefaultsToNull()
    {
        $this->assertNull($this->loop->getState("foobar"));
    }

    public function testGetsPreviouslySetValue($value)
    {
        $this->loop->setState("foobar", $value);
        $this->assertSame($value, $this->loop->getState("foobar"));
    }

    public function dataProviderTestGetsPreviouslySetValue()
    {
        return $this->provideValues();
    }

    public function testGetsPreviouslySetValueViaAccessor($value)
    {
        Loop::setState("foobar", $value);
        $this->assertSame($value, Loop::getState("foobar"));
    }

    public function dataProviderTestGetsPreviouslySetValueViaAccessor()
    {
        return $this->provideValues();
    }

    public function provideValues()
    {
        return [
            ["string"],
            [42],
            [1.001],
            [true],
            [false],
            [null],
            [new \StdClass],
        ];
    }
}
