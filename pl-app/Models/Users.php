<?php

namespace App\Models;

use App\Models\ORM\Base;

final class Users extends Base
{
    protected static array $columns = ["*", "id", "name", "email", "created_at", "updated_at"];
    protected static string $table = 'users';

    /**
     * Создать пользователя, если не существует
     * @param string $name имя пользователя
     * @param string $email почта пользователя
     * @param array $roles список ролей для добавления
     * @return string
     * @throws \Exception
     */
    public function createIfNotExists(string $name, string $email,array $roles= []): string
    {
        $check_user = $this->all()->where('email', trim($email))->executeGet();

        if (!empty($check_user)) {
           return 'Пользователь с почтой ' . $email . ' уже есть';
        }

        $user = $this->create(['name' => $name, 'email' => $email])->executeSet();

        if (empty($user)){
            return 'Не удалось создать пользователя с почтой:  ' . $email;
        }

        if (!empty($roles)) {
            $userGroups = new UserRoles();
            return $userGroups->addToGroup($user, $roles);
        }

        return 'Пользователь с почтой '. $email . 'и именем' . $user . 'успешно создан и добавлен в группы';
    }

    /**
     * Обновить email пользователя
     * @param string $email старая почта пользователя
     * @param string $newEmail новая почта пользователя
     * @param array $roles список ролей для добавления
     * @return string
     * @throws \Exception
     */
    public function updateEmail(string $email, string $newEmail, array $roles=[]): string
    {
        $checkDuplicate = $this->all()->where('email', trim($newEmail))->executeGet();

        if (!empty($checkDuplicate)) {
            return "Пользователь с таким email уже существует";
        }
        $userId= $this->update("email", $newEmail)->where("email", $email)->executeSet();

        if ($userId !== 0) {
            return "Пользователь с указанным email не найден или новый email совпадает с текущим.";
        }

        if (!empty($roles)) {
            $userRoles= new UserRoles();
            return $userRoles->addToGroup($userId, $roles);
        }

        return 'Пользователь с почтой '. 'успешно создан и добавлен в группы';
    }

    /**
     * Удалить пользователя по id
     * @param int $userId
     * @return string
     * @throws \Exception
     */
    public function deleteUserById(int $userId): string
    {
        $sql = $this->deleteWhere("id", $userId)->executeUpdateOrDelete();

        if ($sql === 0){
            return "пользователь не был найден";
        }

        return "Пользователь успешно удалён";
    }


}