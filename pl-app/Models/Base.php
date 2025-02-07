<?php


namespace app\Models;
abstract class Base
{
    abstract public function selectAll():array;
    abstract public function selectOne(int $id): array;
    abstract public function insert(array $data): array;
}