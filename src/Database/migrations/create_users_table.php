<?php

use Src\Database\Database;

require_once __DIR__ . '/../../Core/Autoload.php';
new Autoload();

$db = Database::getInstance();

$query = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        drink_counter INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=INNODB;
";

try {
    $db->exec($query);
    echo json_encode(["success" => "Tabela users criada com sucesso."]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

// docker exec -it php_app php src/Database/migrations/create_users_table.php
