<?php

namespace HugPHP\Telegram;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

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
        $this->mergeConfigFrom(__DIR__ . '/../config/telegram.php', 'telegram');

        // Register the Telegram service
        $this->app->singleton('telegram', function () {
            $botToken = config('telegram.bot_token');

            Log::info('Telegram Bot Token: ' . var_export($botToken, true));
            
            if (!is_string($botToken) || empty($botToken)) {
                throw new InvalidArgumentException('The Telegram bot token is missing or invalid. Please set TELEGRAM_BOT_TOKEN in your .env file.');
            }

            return new Telegram($botToken);
        });
    }
}
