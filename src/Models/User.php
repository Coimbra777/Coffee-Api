<?php

namespace Src\Models;

use Src\Database\Database;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
}
