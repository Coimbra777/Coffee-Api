<?php

namespace Src\Database;

use PDO;
use PDOException;

class Database
{
    private static $instance;

    public static function getInstance()
    {
        if (!self::$instance) {
            $host = 'mysql_db';
            $db = 'database';
            $user = 'user';
            $pass = 'user123';
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

            try {
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die(json_encode(['error' => 'Erro na conexão com o banco: ' . $e->getMessage()]));
            }
        }

        return self::$instance;
    }
}
