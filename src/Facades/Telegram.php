<?php

namespace HugPHP\Telegram\Facades;

use Illuminate\Support\Facades\Facade;

class Telegram extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'telegram';
    }
}
