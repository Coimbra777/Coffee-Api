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
}
