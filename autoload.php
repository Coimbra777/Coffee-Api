<?php

spl_autoload_register(function ($class) {
    $class = str_replace('Src\\', '', $class);
    $path = __DIR__ . '/src/' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($path)) {
        require_once $path;
    }
});
