<?php

namespace App\Models\ORM;

class Builder {
    protected string $sql = "";

    protected string $table;

    protected array $result;

    protected array $params;

    protected Executor $executor;

    public function __construct(){
        $this->executor = new Executor();
    }
    public function setSql(string $sql): self {
        $this->sql .= $sql;
        return $this;
    }

    public function setTable(string $table): self {
        $this->table = $table;
        return $this;
    }

    public function setResult(array $result): self {
        $this->result = $result;
        return $this;
    }

    public function setParams(array $params): self {
        $this->params = $params;
        return $this;
    }

    public function getSql(): string {
        return $this->sql;
    }

    public function getTable(): string {
        return $this->table;
    }

    public function getResult(): array {
        return $this->result;
    }

    public function getParams(): array {
        return $this->params;
    }

    public function get(): object
    {
        return $this->executor->executeSelect($this);
    }

}