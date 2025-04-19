<?php

namespace HugPHP\Telegram;

use Illuminate\Support\ServiceProvider;

class TelegramServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish configuration file (optional)
        $this->publishes([
            __DIR__.'/../config/telegram.php' => config_path('telegram.php'),
        ], 'config');
    }

    public function register()
    {
        // Register the Telegram service
        $this->app->singleton('telegram', function () {
            return new Telegram(config('telegram.bot_token'));
        });
    }
}
