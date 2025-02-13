<?php

namespace App\Models;

use App\Models\ORM\Base;
use PHPUnit\Runner\Exception;

class UserRoles extends Base{

    protected static array $columns = ["*", "user_id", "roles_id"];

    protected static string $table = 'user_roles';

    /**
     * Добавить пользователя в одну или несколько групп
     * @param int $userId ID пользователя
     * @param array $groupIds Массив ID групп
     * @return string
     * @throws Exception
     */
    public function addToGroup(int $userId, array $groupIds): string
    {
        $roles = new Roles;

        $groupExists = $roles->all(["id"])->executeGet();
        $groupIdsFromDb = array_column($groupExists, 'id');

        foreach ($groupIds as $groupId) {

            if (!in_array($groupId, $groupIdsFromDb) ){
                throw new \InvalidArgumentException("Нет группы с таким id " . $groupId);
            }

            $this->create(['user_id' => $userId, 'roles_id' => $groupId])->executeSet();
        }

        return 'Пользователь успешно добавлен в группы';
    }

    public function getUsersRoles(): array{
        $result = $this->customSQl("
        SELECT users.name AS user_name, roles.name AS role_name
        FROM user_roles
        JOIN users ON user_roles.user_id = users.id
        JOIN roles ON user_roles.roles_id = roles.id",
            []
        )->executeGet();

        return $result;
    }

}