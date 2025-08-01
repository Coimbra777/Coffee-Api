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

    /**
     *  Retorna todos os usuários cadastrados
     */
    public function all()
    {
        // $stmt = $this->db->query("SELECT id, name, email FROM users");
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     *  Cria um novo usuário
     */
    public static function register($name, $email, $password)
    {
        $db = Database::getInstance();

        $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return ['error' => 'Email já cadastrado'];
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hash);

        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['error' => 'Falha ao criar usuário'];
        }
    }

    /**
     *  Atualiza um usuário
     */
    public function update($id, $data)
    {
        if (isset($data['_method'])) {
            unset($data['_method']);
        }

        if (empty($data)) {
            return ['error' => 'Nenhum dado fornecido'];
        }

        if (isset($data['email'])) {
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return ['error' => 'Email já está em uso por outro usuário'];
            }
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
            return [
                'success' => true,
                'user' => $this->find($id)
            ];
        }

        return ['error' => 'Falha ao atualizar usuário'];
    }

    /**
     *  Exclui um usuário
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['error' => 'Falha ao excluir usuário'];
        }
    }

    /**
     *  Busca um usuário pelo ID
     */
    public function find($id)
    {
        $stmt = $this->db->query("SELECT id, name, email FROM users WHERE id = $id");
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     *  Busca um usuário pelo email
     */
    public static function findByEmail($email)
    {
        $db = Database::getInstance();

        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     *  Busca um usuário pelo token
     */
    public static function findByToken(string $token)
    {
        $db = Database::getInstance();

        $stmt = $db->prepare("SELECT * FROM users WHERE token = :token LIMIT 1");
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    /**
     *  Atualiza o token de um usuário
     */
    public static function updateToken($id, $token)
    {
        $db = Database::getInstance();

        $stmt = $db->prepare("UPDATE users SET token = :token WHERE id = :id");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}
