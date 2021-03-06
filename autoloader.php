<?php

/**
 * Thanks to Aura libraries
 */
spl_autoload_register(function ($class) {
    $prefixes = [
        "App\\" => [
            __DIR__ . "/App"
        ],
        "Sim\\" => [
            __DIR__ . "/vendor/simplicity-framework"
        ],
        "Tests\\" => [
            __DIR__ . "/Tests"
        ],
        "Pecee\\" => [
            __DIR__ . "/vendor/simple-php-router/src/Pecee"
        ],
    ];

    foreach ($prefixes as $prefix => $dirs) {
        // does the requested class match the namespace prefix
        $prefix_len = strlen($prefix);
        if (substr($class, 0, $prefix_len) !== $prefix) {
            continue;
        }

        // strip the prefix off the class
        $class = substr($class, $prefix_len);

        // a partial filename
        $part = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $class) . '.php';

        // go through the directories to find classes
        foreach ($dirs as $dir) {
            $dir = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $dir);
            $file = $dir . DIRECTORY_SEPARATOR . $part;
            if (is_readable($file)) {
                require $file . '';
                return;
            }
        }
    }
});
