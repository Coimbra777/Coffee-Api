<?php

namespace Src\Controllers;

use Src\Core\Response;
use Src\Models\User;
use Src\Services\AuthService;
use Src\Core\Validators\AuthValidator;

class AuthController
{
    protected  $response;
    protected $authValidator;
    protected $authService;

    public function __construct(
        Response $response,
        AuthValidator $authValidator,
        AuthService $authService
    ) {
        $this->response = $response;
        $this->authValidator = $authValidator;
        $this->authService = $authService;
    }

    /**
     * Faz login e retorna o token
     */
    public function login()
    {
        $input = json_decode(file_get_contents("php://input"), true) ?? $_POST;

        if (empty($input)) {
            $this->response->error("Dados de login não fornecidos.", 400);
        }

        $validatedAuth = $this->authValidator->validateLogin($input);

        if (!$validatedAuth) {
            return $this->response->validation($this->authValidator->getErrors());
        }

        $auth = $this->authService->login($input['email'], $input['password']);

        if ($auth['success']) {
            return $this->response->success('Login realizado com sucesso', $auth);
        }

        return $this->response->error($auth['message'], $auth['status']);
    }

    /**
     * Cria um novo usuário
     */
    public function register()
    {
        $input = json_decode(file_get_contents("php://input"), true) ?? $_POST;

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
