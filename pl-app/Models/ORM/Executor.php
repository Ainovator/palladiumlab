<?php

namespace App\Models\ORM;

use App\Core\Database\DatabaseConnection;

class Executor
{
    private DatabaseConnection $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::getInstance();
    }

    /**
     * Выполнить запрос для операции "SELECT"
     * @param string $sql строка запроса к базе данных
     * @param array $params параметры для подстановки в запрос
     * @return array список возвращаемых строк
     */
    public function executeSelect(string $sql, array $params=[]): array
    {
        $stmt = $this->db->prepare($sql);

        $stmt->execute($params);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Выполнить запрос для операции "INSERT"
     * @param string $sql строка запроса к базе данных
     * @param array $params параметры для подстановки в запрос
     * @return int id последней вставленной записи
     */
    public function executeInsert(string $sql, array $params=[]): int
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $this->db->lastInsertId();
    }

    /**
     * Выполнить запрос для операций "DELETE" или "UPDATE"
     * @param string $sql строка запроса к базе данных
     * @param array $params параметры для подстановки в запрос
     * @return int количество затронутых строк
     */
    public function executeUpdateOrDelete(string $sql, array $params=[]): int
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount();
    }
}