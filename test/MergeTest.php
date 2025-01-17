<?php

namespace Amp\Test;

use Amp\Delayed;
use Amp\Iterator;
use Amp\Loop;
use Amp\PHPUnit\TestException;
use Amp\Producer;

class MergeTest extends BaseTest
{
    public function dataProviderTestMerge(): array
    {
        return [
            [[\range(1, 3), \range(4, 6)], [1, 4, 2, 5, 3, 6]],
            [[\range(1, 5), \range(6, 8)], [1, 6, 2, 7, 3, 8, 4, 5]],
            [[\range(1, 4), \range(5, 10)], [1, 5, 2, 6, 3, 7, 4, 8, 9, 10]],
        ];
    }

    /**
     * @param array $iterators
     * @param array $expected
     */
    public function testMerge(array $iterators, array $expected): void
    {
        Loop::run(function () use ($iterators, $expected) {
            $iterators = \array_map(function (array $iterator): Iterator {
                return Iterator\fromIterable($iterator);
            }, $iterators);

            $iterator = Iterator\merge($iterators);

            while (yield $iterator->advance()) {
                $this->assertSame(\array_shift($expected), $iterator->getCurrent());
            }
        });
    }

    static $dependsTestMergeWithDelayedEmits = 'testMerge';

    public function testMergeWithDelayedEmits(): void
    {
        Loop::run(function () {
            $iterators = [];
            $values1 = [new Delayed(10, 1), new Delayed(50, 2), new Delayed(70, 3)];
            $values2 = [new Delayed(20, 4), new Delayed(40, 5), new Delayed(60, 6)];
            $expected = [1, 4, 5, 2, 6, 3];

            $iterators[] = new Producer(function (callable $emit) use ($values1) {
                foreach ($values1 as $value) {
                    yield $emit($value);
                }
            });

            $iterators[] = new Producer(function (callable $emit) use ($values2) {
                foreach ($values2 as $value) {
                    yield $emit($value);
                }
            });

            $iterator = Iterator\merge($iterators);

            while (yield $iterator->advance()) {
                $this->assertSame(\array_shift($expected), $iterator->getCurrent());
            }
        });
    }

    static $dependsTestMergeWithFailedIterator = 'testMerge';

    public function testMergeWithFailedIterator(): void
    {
        Loop::run(function () {
            $exception = new TestException;
            $producer = new Producer(function (callable $emit) use ($exception) {
                yield $emit(1); // Emit once before failing.
                throw $exception;
            });

            $iterator = Iterator\merge([$producer, Iterator\fromIterable(\range(1, 5))]);

            try {
                while (yield $iterator->advance()) {
                }
                $this->fail("The exception used to fail the iterator should be thrown from advance()");
            } catch (TestException $reason) {
                $this->assertSame($exception, $reason);
            }
        });
    }

    public function testNonIterator(): void
    {
        $this->expectException(\TypeError::class);

        Iterator\merge([1]);
    }
}
