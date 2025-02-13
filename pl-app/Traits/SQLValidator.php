<?php

namespace App\Traits;

use Exception;

trait SQLValidator
{
    /**
     * Валидация столбцов на разрешённые
     * @param array|string $columns
     * @return void
     * @throws Exception
     */
    public function validateColumns(array|string $columns): void
    {

        if (!is_array($columns)) {
            $columns = [$columns];
        }

        foreach ($columns as $column) {

            if (!in_array($column, static::$columns)) {
                throw new \InvalidArgumentException("Недопустимое имя '$column' для столбца");
            }

        }
    }
}
