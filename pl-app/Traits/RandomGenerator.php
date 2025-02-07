<?php

namespace App\Traits;

trait RandomGenerator{
    /**
     * Создаёт имя случайного пользователя
     * @return string
     * @throws \Random\RandomException
     */
    public function generateRandomUsername(){
        return 'user'.bin2hex(random_bytes(4));

    }

    /**
     * Создаёт случайную почту для пользователя
     * @return string
     */
    public function generateRandomEmail() {
        return $this->generateRandomUsername() . '@example.com';
    }
}