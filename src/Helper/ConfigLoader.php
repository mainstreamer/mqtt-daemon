<?php

namespace App\Helper;


class ConfigLoader
{
    public function load(): array
    {
        $dsn = 'mysql:host=0.0.0.0;dbname=weast';
        $user = 'root';
        $pass = 'weastroot';

        return [$dsn,$user,$pass];
    }
}