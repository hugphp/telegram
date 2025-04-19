<?php

namespace Tests;

use HugPHP\Telegram\TelegramServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            TelegramServiceProvider::class,
        ];
    }
}