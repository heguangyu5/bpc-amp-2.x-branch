<?php

if (defined('__BPC__')) {
    require 'React/Promise/autoload.php';
} else {
    require __DIR__ . '/../../bpc-reactphp-promise-2.x-branch/src/autoload.php';
}

require __DIR__ . '/functions.php';
require __DIR__ . '/Internal/functions.php';

spl_autoload_register(function ($class) {
    if (strpos($class, 'Amp\\') === 0) {
        $class = substr($class, strlen('Amp\\'));
        require __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    }
});
