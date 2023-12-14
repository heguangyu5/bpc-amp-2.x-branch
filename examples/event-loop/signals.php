#!/usr/bin/env php
<?php

if (defined('__BPC__')) {
    require 'Amp/autoload.php';
} else {
    require __DIR__ . '/../../lib/autoload.php';
}

use Amp\Loop;

print "Press Ctrl+C to exit..." . PHP_EOL;

Loop::onSignal(SIGINT, function () {
    print "Caught SIGINT, exiting..." . PHP_EOL;
    exit(0);
});

Loop::run();
