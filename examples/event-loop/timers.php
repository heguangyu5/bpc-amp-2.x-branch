#!/usr/bin/env php
<?php

if (defined('__BPC__')) {
    require 'Amp/autoload.php';
} else {
    require __DIR__ . '/../../lib/autoload.php';
}

use Amp\Loop;

print "-- before Loop::run()" . PHP_EOL;

Loop::run(function () {
    Loop::repeat(1000, function () {
        print "++ Executing watcher created by Loop::repeat()" . PHP_EOL;
    });

    Loop::delay(5000, function () {
        print "++ Executing watcher created by Loop::delay()" . PHP_EOL;

        Loop::stop();

        print "++ Loop will continue the current tick and stop afterwards" . PHP_EOL;
    });
});

print "-- after Loop::run()" . PHP_EOL;
