<?php

namespace App\Helper;


class ConfigLoader
{
    public function load(): array
    {
        $config = file('.env');
        $config = array_map('trim', $config);

        return $config;
    }
}