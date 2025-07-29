<?php

class Autoload
{
    public static function load($dir)
    {
        $items = scandir($dir);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;

            $path = $dir . DIRECTORY_SEPARATOR . $item;

            if (is_dir($path)) {
                self::load($path);
            } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
                require_once $path;
            }
        }
    }
}
