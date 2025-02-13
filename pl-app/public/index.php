<?php

use App\Models\Roles;
use App\Models\UserRoles;
use App\Models\Users;

require __DIR__ . '/../vendor/autoload.php';

$user = new Users;
$roles = new Roles;
$userRoles = new UserRoles;


?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление пользователями</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
        }
        pre {
            background: #f8f8f8;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Создаём роли для пользователей</h2>
    <pre>
    <?php
    print_r($roles->createIfNotExists("Admin"));
    echo "<br>";
    print_r($roles->createIfNotExists("User"));
    echo "<br>";
    print_r($roles->createIfNotExists("Manager"));
    ?>
    </pre>

    <h2>Создаём пользователей и добавляем в группы</h2>
    <pre><?php print_r($user->createIfNotExists("Michael", "example_1@mail.ru", [1, 2])); ?></pre>

    <h2>Создаём пользователя и добавляем в группу</h2>
    <pre><?php print_r($user->createIfNotExists("Andrey", "example_2@mail.ru", [1])); ?></pre>

    <h2>Создаём пользователя и добавляем в группу</h2>
    <pre><?php print_r($user->createIfNotExists("Anton", "example_3@mail.ru", [2])); ?></pre>



    <h2>Выводим список всех пользователей</h2>
    <pre><?php print_r($user->all()->executeGet()); ?></pre>

    <h2>Обновляем email пользователя</h2>
    <pre><?php print_r($user->updateEmail("example_1@mail.ru", "new_example@mail.ru")); ?></pre>

    <h2>Проверяем, что email обновился</h2>
    <pre><?php print_r($user->all()->where("email", "new_example@mail.ru")->executeGet()); ?></pre>

    <h2>Удаляем пользователя</h2>
    <pre><?php print_r($user->deleteUserById(1)); ?></pre>

    <h2>Список групп и их пользователей</h2>
    <pre><?php print_r($userRoles->getUsersRoles())?></pre>
</div>

</body>
</html>

