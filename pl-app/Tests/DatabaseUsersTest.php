<?php

namespace App\Tests;

use App\Core\Database\DatabaseConnection;
use PDO;
use PHPUnit\Framework\TestCase;
use App\Traits\RandomGenerator;

class DatabaseUsersTest extends TestCase{
    use RandomGenerator;
    private DatabaseConnection $db;
    /**
     * Создаёт объект подключения перед каждым тестом
     * @return void
     */
    protected function setUp(): void{
        $this->db = DatabaseConnection::getInstance();
    }

    /**
     * Тест на создание пользователя в таблице
     * @return void
     */
    public function testCreateUser(){

        $name = $this->generateRandomUsername();
        $email = $this->generateRandomEmail();

        $sql = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $email = $this->generateRandomEmail();
        }

        $sql = "INSERT INTO users (name, email, created_at, updated_at)
                VALUES (:name, :email, :created_at, :updated_at)";
        $stmt = $this->db->prepare($sql);

        $params = [
            ":name" => $name,
            ":email" => $email,
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s")
        ];

        $stmt->execute($params);

        $this->assertEquals(1, $stmt->rowCount());
    }

    /**
     * Создаёт много случайных пользователей
     * @return void
     */
    public function testCreateUsers(){

        $successfulInserts = 0;

        for($i = 0; $i < 10; $i++){
            $name = $this->generateRandomUsername();
            $email = $this->generateRandomEmail();
            $sql = "INSERT INTO users (name, email, created_at, updated_at)
                    VALUES (:name, :email, :created_at, :updated_at)";
            $stmt = $this->db->prepare($sql);

            $name = $this->generateRandomUsername();
            $email = $this->generateRandomEmail();

            $params = [
                ":name" => $name,
                ":email" => $email,
                ":created_at" => date("Y-m-d H:i:s"),
                ":updated_at" => date("Y-m-d H:i:s")
            ];

            if ($stmt->execute($params)){
                $successfulInserts++;
            }

        }

        $this->assertEquals(10, $successfulInserts);
    }
    public function testDeleteUserById(){

        //Создаём рандомного пользователя для удаления
        $sql = "INSERT INTO users (name, email, created_at, updated_at)
                VALUES (:name, :email, :created_at, :updated_at)";
        $stmt = $this->db->prepare($sql);
        $UserParams = [
            ":name" => $this->generateRandomUsername(),
            ":email" => $this->generateRandomEmail(),
            ":created_at" => date("Y-m-d H:i:s"),
            ":updated_at" => date("Y-m-d H:i:s")
        ];
        $stmt->execute($UserParams);


        //Находим id созданного пользователя
        $sql = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $UserParams[':email']);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = $user['id'];

        $this->assertNotNull($id);

        //Удаляем пользователя
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $DeleteParams = [
            ":id" => $id
        ];
        $stmt->execute($DeleteParams);

        $this->assertEquals(1, $stmt->rowCount());
      }

    public function testUpdateUserById(){
        // Создаём случайного пользователя
        $name = $this->generateRandomUsername();
        $email = $this->generateRandomEmail();

        // Вставляем нового пользователя
        $sqlInsert = "INSERT INTO users (name, email, created_at, updated_at)
            VALUES (:name, :email, :created_at, :updated_at)";
        $stmt = $this->db->prepare($sqlInsert);
        $params = [
            ":name" => $name,
            ":email" => $email,
            ":created_at" => date("Y-m-d H:i:s"),
            ":updated_at" => date("Y-m-d H:i:s")
        ];
        $stmt->execute($params);

        // Получаем id созданного пользователя
        $sqlSelect = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sqlSelect);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = $user['id'];

        $this->assertNotNull($id, "Пользователь не был создан.");

        // Обновляем имя и email пользователя
        $newName = $this->generateRandomUsername();
        $newEmail = $this->generateRandomEmail();
        $sqlUpdate = "UPDATE users SET name = :name, email = :email, updated_at = :updated_at WHERE id = :id";
        $stmt = $this->db->prepare($sqlUpdate);
        $newParams = [
            ":name" => $newName,
            ":email" => $newEmail,
            ":updated_at" => date("Y-m-d H:i:s"),
            ":id" => $id
        ];
        $stmt->execute($newParams);

        // Проверяем, что обновление прошло успешно
        $this->assertEquals(1, $stmt->rowCount(), "Ошибка при обновлении пользователя.");

        // Получаем обновлённого пользователя
        $sqlNewSelect = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sqlNewSelect);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $updatedUser = $stmt->fetch(PDO::FETCH_ASSOC);

        // Сравниваем значения до и после обновления
        $this->assertEquals($newName, $updatedUser['name'], "Имя пользователя не обновлено.");
        $this->assertEquals($newEmail, $updatedUser['email'], "Email пользователя не обновлён.");
    }



}