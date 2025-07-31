<?php

namespace Src\Services;

use Src\Models\CoffeeHistory;

class CoffeeService
{
    private CoffeeHistory $coffeeHistory;

    public function __construct()
    {
        $this->coffeeHistory = new CoffeeHistory();
    }

    /**
     * Incrementa a quantidade de cafés consumidos no dia atual pelo usuário
     */
    public function incrementUserDrink(int $userId, int $quantity = 1): ?array
    {
        $success = $this->coffeeHistory->incrementDailyConsumption($userId, $quantity);

        if (!$success) {
            return null;
        }

        return $this->coffeeHistory->getTodayConsumptionByUser($userId);
    }

    /**
     * Retorna o histórico diário de consumo de Café do usuário
     */
    public function getUserDailyHistory(int $userId): array
    {
        return $this->coffeeHistory->getUserDailyHistory($userId);
    }

    /**
     * Retorna ranking de consumo por dia específico
     */
    public function getRankingByDay(string $date): array
    {
        return $this->coffeeHistory->getRankingByDay($date);
    }

    /**
     * Retorna ranking de consumo nos últimos X dias
     */
    public function getRankingLastDays(int $days): array
    {
        return $this->coffeeHistory->getRankingLastDays($days);
    }
}
