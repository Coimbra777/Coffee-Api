<?php

require_once __DIR__ . '/../../../autoload.php';

use Src\Database\Database;

$db = Database::getInstance();

$query = "
    CREATE TABLE IF NOT EXISTS coffee_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        date DATE NOT NULL,
        quantity INT NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=INNODB;
";

try {
    $db->exec($query);
    echo json_encode(["success" => "Tabela coffee_history criada com sucesso."]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
