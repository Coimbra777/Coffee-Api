<?php

namespace Src\Services;

use Src\Models\User;
use Src\Core\JWT;

class AuthService
{
    public static function login($email, $password)
    {
        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user->password)) {
            $expiresIn = time() + 3600;

            $token = JWT::encode([
                'id' => $user->id,
                'name' => $user->name,
                'expires_in' => $expiresIn,
            ], $GLOBALS['secretJWT']);

            User::updateToken($user->id, $token);

            return [
                'token' => $token,
                'data' => JWT::decode($token, $GLOBALS['secretJWT']),
            ];
        }

        return false;
    }

    public static function verify($token)
    {
        $user = User::findByToken($token);

        if ($user) {
            $decoded = JWT::decode($token, $GLOBALS['secretJWT']);
            return $decoded->expires_in > time();
        }

        return false;
    }
}
