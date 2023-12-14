#!/usr/bin/env php
<?php

use Amp\Loop;

if (defined('__BPC__')) {
    require 'Amp/autoload.php';
} else {
    require __DIR__ . '/../../lib/autoload.php';
}

Loop::setErrorHandler(function (\Throwable $e) {
    echo "error handler -> " . $e->getMessage() . PHP_EOL;
});

try {
    Loop::run(function () {
        // Uncaught exceptions in loop callbacks just bubble out of Loop::run()
        Loop::delay(1000, function () {
            throw new Exception("force exception");
        });
    });

    echo "continuing normally" . PHP_EOL;
} catch (Throwable $loopException) {
    echo "loop bubbled exception caught -> " . $loopException->getMessage() . PHP_EOL;
}
