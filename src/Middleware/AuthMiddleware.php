<?php

namespace Src\Middleware;

use Core\Response;
use Src\Services\AuthService;

class AuthMiddleware
{
    public static function handle()
    {
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            (new Response())->unauthorized("Token de autorização não fornecido.");
            exit;
        }

        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        $token = str_replace('Bearer ', '', $authHeader);

        $auth = AuthService::verify($token);

        if (!$auth) {
            (new Response())->unauthorized("Token inválido.");
            exit;
        }

        if (is_array($auth) && ($auth['expired'] ?? false) === true) {
            (new Response())->unauthorized("Token expirado.");
            exit;
        }

        $_REQUEST['authenticated_user'] = $auth;
    }
}
