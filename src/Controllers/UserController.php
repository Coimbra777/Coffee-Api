<?php

namespace Src\Controllers;

use Src\Models\User;

class UserController

{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function index()
    {
        $users = $this->user->all();
        echo json_encode(['data' => $users]);
    }

    public function store() {}

    public function update() {}

    public function destroy() {}

    public function login() {}
}
