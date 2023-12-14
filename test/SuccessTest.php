<?php

namespace Amp\Test;

use Amp\Loop;
use Amp\Promise;
use Amp\Success;
use React\Promise\RejectedPromise as RejectedReactPromise;

class SuccessTest extends BaseTest
{
    public function testConstructWithNonException(): void
    {
        $this->expectException(\Error::class);

        new Success($this->getMockBuilder(Promise::class)->getMock());
    }

    public function testOnResolve(): void
    {
        $value = "Resolution value";

        $invoked = 0;
        $callback = function ($exception, $value) use (&$invoked, &$result) {
            ++$invoked;
            $result = $value;
        };

        $success = new Success($value);

        $success->onResolve($callback);

        self::assertSame(1, $invoked);
        self::assertSame($value, $result);
    }

    static $dependsTestOnResolveThrowingForwardsToLoopHandlerOnSuccess = 'testOnResolve';

    public function testOnResolveThrowingForwardsToLoopHandlerOnSuccess(): void
    {
        Loop::run(function () use (&$invoked) {
            $invoked = 0;
            $expected = new \Exception;

            Loop::setErrorHandler(function ($exception) use (&$invoked, $expected) {
                ++$invoked;
                $this->assertSame($expected, $exception);
            });

            $callback = function () use ($expected) {
                throw $expected;
            };

            $success = new Success;

            $success->onResolve($callback);
        });

        self::assertSame(1, $invoked);
    }

    public function testOnResolveWithReactPromise(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Success');

        Loop::run(function () {
            $success = new Success;
            $success->onResolve(function ($exception, $value) {
                return new RejectedReactPromise(new \Exception("Success"));
            });
        });
    }

    public function testOnResolveWithGenerator(): void
    {
        $value = 1;
        $success = new Success($value);
        $invoked = false;
        $success->onResolve(function ($exception, $value) use (&$invoked) {
            $invoked = true;
            return $value;
            yield; // Unreachable, but makes function a generator.
        });

        self::assertTrue($invoked);
    }
}
