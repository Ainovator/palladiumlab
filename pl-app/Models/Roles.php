<?php


namespace App\Models;

use App\Models\ORM\Base;

final class Roles extends Base
{
    protected static array $columns = ["*", "id", "name"];
    protected static string $table = 'roles';

    /**
     * Создать группу если не существует
     * @param string $name имя пользователя
     * @return string
     * @throws \Exception
     */
    public function createIfNotExists(string $name): string
    {
        $check_group = $this->all()->where('name', trim($name))->executeGet();

        if (!empty($check_group)) {
            return 'Группа с названием ' . $name. ' уже существует';
        }
        echo $group = $this->create(['name' => $name])->getSql();
        $group = $this->create(['name' => $name])->executeSet();

        if ($group === 0) {
            return 'Не удалось создать группу:  ' . $name;
        } else
            return 'Группа успешно создана';
    }


}