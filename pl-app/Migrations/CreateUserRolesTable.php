<?php

namespace App\Migrations;

use App\Core\Database\DatabaseConnection;

class CreateUserRolesTable
{
    public static function createTable(): void
    {
        $db = DatabaseConnection::getInstance();
        $sql = "CREATE TABLE user_roles (
                user_id INT,
                roles_id INT,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (roles_id) REFERENCES roles (id) ON DELETE CASCADE,
                PRIMARY KEY (user_id, roles_id)
            )";
        $db->exec($sql);
        echo "Таблица user_roles успешно создана или уже существует!\n";
    }
}
