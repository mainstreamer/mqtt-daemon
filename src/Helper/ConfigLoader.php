<?php

namespace App\Helper;


class ConfigLoader
{
    public function load(): array
    {
        //'.env';
        $file = __DIR__.'/../../.env';
        $config = file($file);
        $config = array_map('trim', $config);

        return $config;
    }
}