<?php

namespace App\Helper;


class ConfigLoader
{
    public function load(): array
    {
        $dsn = getenv('DSN') || 'mysql:host=127.0.0.1;dbname=weast';
        $user = getenv('USER') || 'root';
        $pass = getenv('PASS') || 'weastroot';

        return [$dsn,$user,$pass];
    }
}