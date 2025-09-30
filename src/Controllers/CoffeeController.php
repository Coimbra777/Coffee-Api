<?php

namespace Src\Controllers;

use Src\Core\Response;
use Src\Models\User;
use Src\Services\CoffeeService;
use Src\Core\Validators\DrinkValidator;

class CoffeeController
{
    private User $user;
    private Response $response;
    private CoffeeService $coffeeService;

    public function __construct()
    {
        $this->user = new User();
        $this->response = new Response();
        $this->coffeeService = new CoffeeService();
    }

    /**
     * Incrementa a quantidade de cafés consumidos no dia atual pelo usuário
     */
    public function drink($userId)
    {
        $user = $this->user->find($userId);

        if (!$user) {
            return $this->response->notFound('Usuário não encontrado.');
        }

        $authUser = $_REQUEST['authenticated_user']['user'] ?? null;

        if (!$authUser || $authUser->id != $userId) {
            return $this->response->unauthorized("Você não tem permissão para atualizar este usuário.");
        }

        $data = json_decode(file_get_contents("php://input"), true) ?? $_POST;

        $validator = new DrinkValidator();

        if (!$validator->validate($data)) {
            return $this->response->validation($validator->getErrors());
        }

        $validated = $validator->validatedData($data);
        $quantity = $validated['drink'];

        $date = $validated['date'] ?? null;
        $updatedData = $this->coffeeService->incrementUserDrink((int)$userId, $quantity, $date);

        if (!$updatedData) {
            return $this->response->error("Erro ao atualizar contador de cafés.");
        }

        return $this->response->success("Contador de cafés atualizado com sucesso.", $updatedData);
    }

    /**
     * Retorna o histórico diário de consumo de Café do usuário
     */
    public function history($userId)
    {
        $history = $this->coffeeService->getUserDailyHistory((int)$userId);

        return $this->response->success("Histórico de consumo recuperado.", $history);
    }

    /**
     * Retorna ranking de consumo por dia específico
     */
    public function rankingByDay($date)
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $this->response->unprocessableEntity(['date' => 'Formato de data inválido.']);
        }

        $ranking = $this->coffeeService->getRankingByDay($date);

        return $this->response->success("Ranking por dia obtido com sucesso.", $ranking);
    }

    /**
     * Retorna ranking de consumo nos últimos X dias
     */
    public function rankingLastDays($days)
    {
        $days = (int)$days;
        if ($days < 1) {
            return $this->response->unprocessableEntity(['days' => 'O número de dias deve ser maior que zero.']);
        }

        $ranking = $this->coffeeService->getRankingLastDays($days);

        return $this->response->success("Ranking dos últimos {$days} dias obtido com sucesso.", $ranking);
    }
}
