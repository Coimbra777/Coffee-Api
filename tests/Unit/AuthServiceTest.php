<?php

use PHPUnit\Framework\TestCase;
use Src\Core\Response;
use Src\Services\AuthService;

class AuthServiceTest extends TestCase
{
    public function testLoginWithInvalidUser()
    {
        $response = new Response(true);
        $authService = new AuthService($response);
        $result = $authService->login("naoexiste@example.com", "123456");

        $this->assertFalse($result['success']);
        $this->assertEquals(404, $result['status']);
        $this->assertEquals("Usuário não encontrado.", $result['message']);
    }

    public function testVerifyWithInvalidToken()
    {
        $response = new Response(true);
        $authService = new AuthService($response);
        $response = $authService->verify("token_invalido");

        $this->assertFalse($response['success']);
        $this->assertEquals(401, $response['status']);
        $this->assertEquals("Token inválido.", $response['message']);
    }
}
