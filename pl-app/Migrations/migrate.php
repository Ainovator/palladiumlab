<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Migrations\CreateUsersTable;
use App\Migrations\CreateRolesTable;
use App\Migrations\CreateUserRolesTable;


CreateUsersTable::createTable();
CreateRolesTable::createTable();
CreateUserRolesTable::createTable();
