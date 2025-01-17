#!/usr/bin/env php
<?php

if (defined('__BPC__')) {
    require 'Amp/autoload.php';
} else {
    require __DIR__ . '/../../lib/autoload.php';
}

use Amp\Delayed;
use Amp\Emitter;
use Amp\Loop;

Loop::run(function () {
    try {
        $emitter = new Emitter;

        Loop::defer(function () use ($emitter) {
            // Listener emits all values at once.
            $emitter->emit(1);
            $emitter->emit(2);
            $emitter->emit(3);
            $emitter->emit(4);
            $emitter->emit(5);
            $emitter->emit(6);
            $emitter->emit(7);
            $emitter->emit(8);
            $emitter->emit(9);
            $emitter->emit(10);
            $emitter->complete();
        });

        $iterator = $emitter->iterate();

        while (yield $iterator->advance()) {
            \printf("Emitter emitted %d\n", $iterator->getCurrent());
            yield new Delayed(100); // Listener consumption takes 100 ms.
        }
    } catch (\Throwable $exception) {
        \printf("Exception: %s\n", (string) $exception);
    }
});
