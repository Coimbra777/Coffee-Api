<?php

require_once __DIR__ . '/../../autoload.php';

use Src\Database\Database;

$db = Database::getInstance();

$migrationsPath = __DIR__ . '/migrations';

$files = scandir($migrationsPath);

foreach ($files as $file) {
    if (preg_match('/\.php$/', $file)) {
        require_once $migrationsPath . '/' . $file;
    }
}
