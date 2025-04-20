<?php

declare(strict_types=1);

namespace HugPHP\Telegram\Facades;

use Illuminate\Support\Facades\Facade;

class Telegram extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'telegram';
    }
}
