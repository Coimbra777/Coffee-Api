<?php

namespace Src\Models;

use Src\Database\Database;
use PDO;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all()
    {
        $stmt = $this->db->query("SELECT id, name, email, drink_counter FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($name, $email, $password)
    {
        $db = Database::getInstance();

        // Verifica se já existe usuário com esse email
        $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return ['error' => 'Email already registered'];
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hash);

        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['error' => 'Failed to create user'];
        }
    }

    public static function findByEmail($email)
    {
        $db = Database::getInstance();

        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function findByToken($token)
    {
        $db = Database::getInstance();

        $stmt = $db->prepare("SELECT * FROM users WHERE token = :token LIMIT 1");
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function updateToken($id, $token)
    {
        $db = Database::getInstance();

        $stmt = $db->prepare("UPDATE users SET token = :token WHERE id = :id");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}
