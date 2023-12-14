<?php

if (defined('__BPC__')) {
    require 'React/Promise/autoload.php';
    require 'Amp/functions.php';
    require 'Amp/Internal/functions.php';
} else {
    require __DIR__ . '/../../bpc-reactphp-promise-2.x-branch/src/autoload.php';
    require __DIR__ . '/../lib/functions.php';
    require __DIR__ . '/../lib/Internal/functions.php';
}

spl_autoload_register(function ($class) {
    if (strpos($class, 'Amp\\Test\\') === 0) {
        $class = substr($class, strlen('Amp\\Test\\'));
        require __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    } elseif (strpos($class, 'Amp\\PHPUnit\\') === 0) {
        $class = substr($class, strlen('Amp\\PHPUnit\\'));
        require __DIR__ . '/PHPUnit/' . str_replace('\\', '/', $class) . '.php';
    } elseif (strpos($class, 'Amp\\') === 0) {
        $class = substr($class, strlen('Amp\\'));
        if (defined('__BPC__')) {
            require 'Amp/' . str_replace('\\', '/', $class) . '.php';
        } else {
            require __DIR__ . '/../lib/' . str_replace('\\', '/', $class) . '.php';
        }
    }
});
