<?php

namespace Src\Services;

use Src\Models\User;
use Src\Core\JWT;

class AuthService
{
    public function login($email, $password)
    {
        $user = User::findByEmail($email);

        if (!$user) {
            return [
                'success' => false,
                'status' => 404,
                'message' => 'Usuário não encontrado.'
            ];
        }

        if (!password_verify($password, $user->password)) {
            return [
                'success' => false,
                'status' => 401,
                'message' => 'Usuário ou senha inválidos.'
            ];
        }

        $token = JWT::encode([
            'id' => $user->id,
            'name' => $user->name,
            'expires_in' => time() + 999999,
        ], $GLOBALS['secretJWT']);

        User::updateToken($user->id, $token);

        return [
            'success' => true,
            'token' => $token,
            'data' => JWT::decode($token, $GLOBALS['secretJWT']),
        ];
    }

    public static function verify($token)
    {
        try {
            $decoded = JWT::decode($token, $GLOBALS['secretJWT']);

            if (isset($decoded->expires_in) && $decoded->expires_in < time()) {
                return [
                    'success' => false,
                    'status' => 401,
                    'message' => 'Token expirado.'
                ];
            }

            $userId = $decoded->sub ?? $decoded->id ?? null;

            if (!$userId) {
                return [
                    'success' => false,
                    'status' => 401,
                    'message' => 'Token inválido.'
                ];
            }

            $userModel = new User();
            $user = $userModel->findByToken($token);

            if (!$user) {
                return [
                    'success' => false,
                    'status' => 401,
                    'message' => 'Token inválido.'
                ];
            }

            return [
                'success' => true,
                'user' => $user
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status' => 401,
                'message' => 'Token inválido.'
            ];
        }
    }
}
