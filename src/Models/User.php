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

    public static function register($name, $email, $password)
    {
        $db = Database::getInstance();

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

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute($data);
    }

    public function update($id, $data)
    {
        if (isset($data['_method'])) {
            unset($data['_method']);
        }

        if (empty($data)) {
            return ['error' => 'No data provided'];
        }

        $sql = "UPDATE users SET ";
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            $fields[] = "{$key} = ?";
            $values[] = $key === 'password' ? password_hash($value, PASSWORD_DEFAULT) : $value;
        }

        $sql .= implode(', ', $fields) . " WHERE id = ?";
        $values[] = $id;

        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute($values);

        if ($success) {
            return ['success' => true];
        } else {
            return ['error' => 'Failed to update user'];
        }
    }

    public function find($id)
    {
        $stmt = $this->db->query("SELECT id, name, email, drink_counter FROM users WHERE id = $id");
        return $stmt->fetch(PDO::FETCH_OBJ);
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
