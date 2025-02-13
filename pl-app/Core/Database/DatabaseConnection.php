<?php

namespace App\Core\Database;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Singleton класс подключения к базе данных
 */
class DatabaseConnection extends PDO
{
    private static ?DatabaseConnection $instance = null;

    private const PDO_OPTIONS = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    private function __construct(){

        $config = require __DIR__ . '/../../Config/db.php';

        $dsn = "mysql:host=". $config["host"] .";dbname=".$config["database"].";charset=utf8mb4";
        $username = $config["user"] ?? "empty";
        $password = $config["password"] ?? "empty";

        try{
            parent::__construct($dsn, $username, $password, self::PDO_OPTIONS);
        } catch (PDOException $e){
            throw new RuntimeException("Подключение не удалось: ". $e->getMessage());
        }
    }
    /**
     * Возвращает единственный экземпляр класса
     * @return DatabaseConnection
     */
    public static function getInstance(): DatabaseConnection{
        if(self::$instance === null){
            self::$instance = new self;
        }
        return self::$instance;
    }
}

