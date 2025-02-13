<?php

namespace App\Traits;

trait QueryConstructor{
    public function addMethod(string $method): string
    {
        return $method . " ";
    }

    public function addColumns(array $columns): string
    {
        return implode(', ', $columns) . " ";;
    }

    public function addTable(string $table): string
    {
        return "FROM " . $table;
    }

    public function addWhere(string $column, string $operator, $value): string
    {
        return " WHERE " . $column . " " . $operator . " ?";
    }

    public function addAnd(string $column, string $operator, $value): string
    {
        return " AND " . $column . " " . $operator . " ?";
    }

    public function addJoin(string $type, string $table, string $on): self
    {
        $this->sql .= strtoupper($type) . " JOIN " . $table . " ON " . $on . " ";
        return $this;
    }

    /**
     * Добавить GROUP BY
     * @param array $columns
     * @return $this
     */
    public function addGroupBy(array $columns): self
    {
        $this->sql .= "GROUP BY " . implode(', ', $columns) . " ";
        return $this;
    }

    /**
     * Добавить ORDER BY
     * @param array $columns
     * @param string $direction
     * @return $this
     */
    public function addOrderBy(array $columns, string $direction = 'ASC'): self
    {
        $this->sql .= "ORDER BY " . implode(', ', $columns) . " " . strtoupper($direction) . " ";
        return $this;
    }

    /**
     * Добавить LIMIT
     * @param int $limit
     * @return $this
     */
    public function addLimit(int $limit): self
    {
        $this->sql .= "LIMIT " . $limit . " ";
        return $this;
    }

    /**
     * Добавить OFFSET
     * @param int $offset
     * @return $this
     */
    public function addOffset(int $offset): self
    {
        $this->sql .= "OFFSET " . $offset . " ";
        return $this;
    }
}