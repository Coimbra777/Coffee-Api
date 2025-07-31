<?php

namespace Core;

class Response
{
    private function send(array $body, int $statusCode)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($body);
        exit;
    }

    public function json(array $data = [], int $statusCode = 200)
    {
        $this->send([
            'status' => 'success',
            'data' => $data
        ], $statusCode);
    }

    public function success(string $message = 'Operação realizada com sucesso.', $data = [], int $statusCode = 200)
    {
        $this->send([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public function error(string $message = "Ocorreu um erro", int $statusCode = 400)
    {
        $this->send([
            'status' => 'error',
            'message' => $message
        ], $statusCode);
    }

    public function unauthorized(string $message = "Acesso não autorizado")
    {
        $this->error($message, 401);
    }

    public function notFound(string $message = "Recurso não encontrado")
    {
        $this->error($message, 404);
    }

    public function conflict(string $message = "Conflito de dados")
    {
        $this->error($message, 409);
    }

    public function validation(array $errors)
    {
        $this->send([
            'status' => 'error',
            'message' => 'Erro de validação',
            'errors' => $errors
        ], 422);
    }

    public function unprocessableEntity(array $errors)
    {
        $this->validation($errors);
    }
}
