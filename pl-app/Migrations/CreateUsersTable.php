<?php

namespace App\Migrations;

use App\Core\Database\DatabaseConnection;

class CreateUsersTable
{
    public static function createTable(): void
    {
        $db = DatabaseConnection::getInstance();
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(150) UNIQUE NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $db->exec($sql);
        echo "Таблица users успешно создана или уже существует!\n";
    }
}
