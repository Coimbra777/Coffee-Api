<?php

namespace Src\Models;

use Src\Database\Database;

class CoffeeHistory
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Incrementa a quantidade de cafés consumidos no dia atual pelo usuário
     */
    public function incrementDailyConsumption(int $userId, int $quantity = 1, ?string $date = null): bool
    {
        $sql = "INSERT INTO coffee_history (user_id, date, quantity)
            VALUES (:user_id, :date, :quantity)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $userId,
            ':date' => $date ?? date('Y-m-d'),
            ':quantity' => $quantity,
        ]);
    }

    /**
     * Retorna o histórico diário de consumo de café do usuário
     */
    public function getUserDailyHistory(int $userId): array
    {
        $sql = "SELECT date, quantity
                FROM coffee_history
                WHERE user_id = :user_id
                ORDER BY date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Retorna ranking de consumo por dia específico
     */
    public function getRankingByDay(string $date): array
    {
        $sql = "SELECT u.name, ch.quantity
                FROM coffee_history ch
                JOIN users u ON u.id = ch.user_id
                WHERE ch.date = :date
                ORDER BY ch.quantity DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':date' => $date]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Retorna ranking de consumo nos últimos X dias
     */
    public function getRankingLastDays(int $days): array
    {
        $sql = "SELECT u.name, SUM(ch.quantity) as total_quantity
            FROM coffee_history ch
            JOIN users u ON u.id = ch.user_id
            WHERE ch.date >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
            GROUP BY u.id
            ORDER BY total_quantity DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':days', $days, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Retorna a quantidade de cafés consumidos pelo usuário no dia especificado
     */
    public function getConsumptionByUserOnDate(int $userId, ?string $date = null): ?array
    {
        $sql = "SELECT u.name, SUM(ch.quantity) as quantity
            FROM coffee_history ch
            JOIN users u ON u.id = ch.user_id
            WHERE ch.user_id = :user_id AND ch.date = :date
            GROUP BY u.name";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':date' => $date ?? date('Y-m-d'),
        ]);

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }
}
