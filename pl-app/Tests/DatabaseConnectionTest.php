<?php declare(strict_types=1);

namespace App\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database\DatabaseConnection;
use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    private DatabaseConnection $db;

    /**
     * Создаёт объект подключения перед каждым тестом
     * @return void
     */
    protected function setUp(): void
    {
        $this->db = DatabaseConnection::getInstance();
    }

    /**
     * Проверяет, что объект подключения к базе данных успешно создаётся.
     * @return void
     */
    public function testDatabaseConnectionInstance(): void
    {
        $this->assertInstanceOf(DatabaseConnection::class, $this->db);
    }

    /**
     * Проверяет, что соединение с базой данных активно и выполняет SQL-запросы.
     * @return void
     */
    public function testDatabaseConnectionIsActive(): void
    {
        $stmt = $this->db->query('SELECT 1');
        $result = $stmt->fetchColumn();

        $this->assertEquals(1, $result);
    }
}
