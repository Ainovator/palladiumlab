<?php

namespace App\Migrations;

use App\Core\Database\DatabaseConnection;

class CreateRolesTable
{
    public static function createTable(): void
    {
        $db = DatabaseConnection::getInstance();
        $sql = "CREATE TABLE IF NOT EXISTS roles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL
        )";
        $db->exec($sql);
        echo "Таблица roles успешно создана или уже существует!\n";
    }
}
