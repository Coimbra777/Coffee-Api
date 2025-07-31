<?php

namespace Src\Controllers;

use Core\Response;
use Src\Core\Validators\DrinkValidator;
use Src\Services\CoffeeService;

class CoffeeController
{
    private Response $response;
    private CoffeeService $coffeeService;

    public function __construct()
    {
        $this->response = new Response();
        $this->coffeeService = new CoffeeService();
    }

    public function drink($userId)
    {
        $auth = $_REQUEST['authenticated_user'] ?? null;
        $authUser = is_array($auth) && isset($auth['user']) ? $auth['user'] : null;

        if (!$authUser || !is_object($authUser) || $authUser->id != $userId) {
            return $this->response->unauthorized("Você não tem permissão para registrar café para este usuário.");
        }

        $data = json_decode(file_get_contents("php://input"), true) ?? $_POST;

        $validator = new DrinkValidator();

        if (!$validator->validate($data)) {
            return $this->response->validation($validator->getErrors());
        }

        $validated = $validator->validatedData($data);
        $quantity = $validated['drink'];

        $success = $this->coffeeService->incrementUserDrink((int)$userId, $quantity);

        if (!$success) {
            return $this->response->error("Erro ao atualizar contador de cafés.");
        }

        return $this->response->success("Contador de cafés atualizado com sucesso.");
    }

    public function history($userId)
    {
        $history = $this->coffeeService->getUserDailyHistory((int)$userId);

        return $this->response->success("Histórico de consumo recuperado.", $history);
    }

    public function rankingByDay($date)
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $this->response->unprocessableEntity(['date' => 'Formato de data inválido.']);
        }

        $ranking = $this->coffeeService->getRankingByDay($date);

        return $this->response->success("Ranking por dia obtido com sucesso.", $ranking);
    }

    public function rankingLastDays($days)
    {
        $days = (int)$days;
        if ($days < 1) {
            return $this->response->unprocessableEntity(['days' => 'O número de dias deve ser maior que zero.']);
        }

        $ranking = $this->coffeeService->getRankingLastDays($days);

        return $this->response->success("Ranking dos últimos dias obtido com sucesso.", $ranking);
    }
}
