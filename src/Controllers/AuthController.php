<?php

namespace Src\Controllers;

use Core\Response;
use Src\Models\User;
use Src\Services\AuthService;
use Src\Core\Validators\AuthValidator;

class AuthController
{
    private Response $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    public function login()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        if (!$input) $input = $_POST;

        $validator = new AuthValidator();

        if (!$validator->validateLogin($input)) {
            return $this->response->validation($validator->getErrors());
        }

        $validated = $validator->validatedData($input);
        $auth = AuthService::login($validated['email'], $validated['password']);

        if ($auth) {
            return $this->response->json($auth);
        }

        return $this->response->unauthorized("Email ou senha inválidos.");
    }

    public function register()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        if (!$input) $input = $_POST;

        $validator = new AuthValidator();

        if (!$validator->validateRegister($input)) {
            return $this->response->validation($validator->getErrors());
        }

        $validated = $validator->validatedData($input);
        $name = $validated['name'] ?? '';
        $email = $validated['email'];
        $password = $validated['password'];

        $result = User::register($name, $email, $password);

        if (isset($result['success']) && $result['success'] === true) {
            return $this->response->json(['message' => 'Usuário cadastrado com sucesso']);
        }

        if (isset($result['error']) && str_contains($result['error'], 'Email já cadastrado.')) {
            return $this->response->conflict("Email já cadastrado.");
        }

        return $this->response->error($result['error'] ?? 'Erro ao cadastrar usuário');
    }
}
