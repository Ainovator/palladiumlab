<?php


namespace App\Models\ORM;

use App\Traits\SQLValidator;
use Exception;


abstract class Base
{

    use SQLValidator;

    /**
     * Названия таблицы для обращения
     * @var string
     */
    protected static string $table;

    /**
     * Список столбцов таблицы
     * @var string
     */
    protected static array $columns;

    /**
     * Строка для выполнения запроса
     * @var string
     */
    protected string $sql;

    /**
     * Массив параметров для запроса
     * @var array
     */
    protected array $params = [];

    /**
     * Объект для выполнения запросов в db
     * @var \App\Models\ORM\Executor
     */
    protected Executor $executor;

    public function __construct()
    {
        if (!property_exists($this, 'table')) {
            throw new Exception('Таблица не инициализирована');
        }

        $this->executor = new Executor();
    }

    /**
     * Вернуть все записи из таблицы по выбранным колонкам
     * либо [*]
     * @param array $columns
     * @return $this
     * @throws Exception
     */
    public function All(array $columns = ["*"]): self
    {
        $this->validateColumns($columns);

        $this->sql = sprintf("SELECT %s FROM %s", implode(", ", $columns), static::$table);

        return $this;
    }

    /**
     * Добавить параметр запроса WHERE с оператором "="
     * @param string $column
     * @param string $value
     * @return $this
     */
    public function Where(string $column, string $value): self
    {
        $column = trim($column);

        $this->validateColumns($column);

        $operator = str_contains($this->sql, "WHERE") ? "AND" : "WHERE";

        $this->sql .= sprintf(" %s %s = :%s", $operator, $column, $column);

        $this->params[":$column"] = $value;

        return $this;
    }

    /**
     * Добавить параметр сортировки по столбцу
     * @param string $column столбец для сортировки
     * @param string $direction метод сортировки
     * @return $this
     * @throws Exception
     */
    public function sort(string $column, string $direction = 'ASC'): self
    {

        $this->validateColumns($column);

        $direction = strtoupper($direction);

        if (!in_array($direction, ['ASC', 'DESC'])) {
            throw new \InvalidArgumentException("Недопустимое направление сортировки");
        }

        $this->sql .= sprintf(" ORDER BY %s %s", $column, $direction);

        return $this;
    }

    /**
     * Обновить запись в таблице
     * @param string $column
     * @param string $value
     * @return $this
     * @throws Exception
     */
    public function update(string $column, string $value): self
    {
        $this->validateColumns($column);

        $column = trim($column);
        $value = trim($value);

        $this->sql = sprintf("UPDATE %s SET %s = :new_%s", static::$table, $column, $column);

        $this->params[":new_$column"] = $value;

        return $this;
    }

    /**
     * Создать запись в таблице с параметрами
     * @param array $data связанный список параметров
     * @return $this
     * @throws Exception
     */
    public function create(array $data): self
    {
        if (empty ($data)) {
            throw new Exception("Запрос не может быть пустой");
        }

        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $this->sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)", static::$table, $columns, $placeholders);

        foreach ($data as $column => $value) {
            $this->validateColumns($column);
            $this->params[":$column"] = $value;
        }

        return $this;
    }

    /**
     * Удалить запись по идентификатору
     * @param string $column
     * @param string $value
     * @return $this
     * @throws Exception
     */
    public function deleteWhere(string $column, string $value): self
    {
        $this->validateColumns($column);

        $column = trim($column);
        $value = trim($value);

        $this->sql = sprintf("DELETE FROM %s WHERE %s = :%s", static::$table, $column, $column);
        $this->params[":$column"] = $value;

        return $this;
    }

    /**
     * Запрос на возврат строк из db
     * @return array возвращает массив найденных строк
     */
    public function executeGet(): array
    {
        $result = $this->executor->executeSelect($this->sql, $this->params);

        $this->params = [];
        $this->sql = "";

        return $result;
    }

    /**
     * Запрос на добавление в таблицу, возвращает id последнего добавления
     * @return int
     */
    public function executeSet(): int
    {
        $result = $this->executor->executeInsert($this->sql, $this->params);

        $this->params = [];
        $this->sql = "";

        return $result;
    }

    /**
     * Запрос на обновление или удаление из таблицы
     * @return int
     */
    public function executeUpdateOrDelete(): int
    {
        $result = $this->executor->executeUpdateOrDelete($this->sql, $this->params);

        $this->params = [];
        $this->sql = "";

        return $result;
    }

    /**
     * Вернуть сформированный sql запрос
     * @return string
     */
    public function getSql(): string
    {
        return $this->sql;
    }

    /**
     * Вернуть список параметров
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Функция для DEV, составление кастомных запросов
     * @param string $sql
     * @param array $params
     * @return $this
     */
    public function customSQl(string $sql, array $params): self
    {
        $this->sql = $sql;
        $this->params = $params;

        return $this;
    }
}