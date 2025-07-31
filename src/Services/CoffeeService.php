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

    public function incrementUserDrink(int $userId, int $quantity = 1): bool
    {
        return $this->coffeeHistory->incrementDailyConsumption($userId, $quantity);
    }

    public function getUserDailyHistory(int $userId): array
    {
        return $this->coffeeHistory->getUserDailyHistory($userId);
    }

    public function getRankingByDay(string $date): array
    {
        return $this->coffeeHistory->getRankingByDay($date);
    }

    public function getRankingLastDays(int $days): array
    {
        return $this->coffeeHistory->getRankingLastDays($days);
    }
}
