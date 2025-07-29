<?php

namespace Src\Controllers;

use Src\Models\User;
use Src\Services\AuthService;

class AuthController
{
    public function login()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            $input = $_POST;
        }

        if (empty($input['email']) || empty($input['password'])) {
            echo json_encode(['error' => 'Login and password are required']);
            return;
        }

        // Passa só login e password para o AuthService
        $auth = AuthService::login($input['email'], $input['password']);

        if ($auth) {
            echo json_encode($auth);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid login or password']);
        }
    }

    public function register()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        if (!$input) {
            $input = $_POST;
        }

        if (empty($input['name']) || empty($input['email']) || empty($input['password'])) {
            echo json_encode(['error' => 'Name, email, and password are required']);
            return;
        }

        $result = User::create($input['name'], $input['email'], $input['password']);

        if (isset($result['success']) && $result['success'] === true) {
            echo json_encode(['message' => 'User registered successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => $result['error'] ?? 'Failed to register user']);
        }
    }
}
