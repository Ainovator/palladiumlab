<?php

namespace app\Models;

use App\Core\Database\DatabaseConnection;
use PDO;

class Users
{
    private  PDO $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::getInstance();
    }

    /**
     * Метод создания пользователя в таблице
     * @param string $username
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function createUser(string $username, string $email, string $password): bool
    {
        if ($this->getUserByEmail($email)) {
            return false;
        }
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $hashedPassword);

        return $stmt->execute();
    }

    public function getUserByEmail(string $email): ?array{

        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

    }
}