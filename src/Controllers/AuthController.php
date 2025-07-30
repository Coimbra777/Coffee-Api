<?php

namespace Src\Controllers;

use Src\Models\User;
use Src\Services\AuthService;
use Src\Core\Validators\AuthValidator;

class AuthController
{
    public function login()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            $input = $_POST;
        }

        $validator = new AuthValidator();

        if (!$validator->validateLogin($input)) {
            http_response_code(422);
            echo json_encode(['errors' => $validator->getErrors()]);
            return;
        }

        $validated = $validator->validatedData($input);

        $auth = AuthService::login($validated['email'], $validated['password']);

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

        $validator = new AuthValidator();

        if (!$validator->validateRegister($input)) {
            http_response_code(422);
            echo json_encode(['errors' => $validator->getErrors()]);
            return;
        }

        $validated = $validator->validatedData($input);
        $name = $validated['name'] ?? '';
        $email = $validated['email'];
        $password = $validated['password'];

        $result = User::register($name, $email, $password);

        if (isset($result['success']) && $result['success'] === true) {
            echo json_encode(['message' => 'User registered successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => $result['error'] ?? 'Failed to register user']);
        }
    }
}
