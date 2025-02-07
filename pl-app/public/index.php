<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Models\Users;

$user = new Users();
$userData = $user->selectUserById(50);
print_r($userData);
