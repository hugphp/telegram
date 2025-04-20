<?php

namespace HugPHP\Telegram;

use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class TelegramServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Publish configuration file (optional)
        $this->publishes([
            __DIR__.'/../config/telegram.php' => config_path('telegram.php'),
        ], 'config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/telegram.php', 'telegram');

        // Register the Telegram service
        $this->app->singleton('telegram', function (): Telegram {
            $botToken = config('telegram.bot_token');

            if (! is_string($botToken) || ($botToken === '' || $botToken === '0')) {
                throw new InvalidArgumentException('The Telegram bot token is missing or invalid. Please set TELEGRAM_BOT_TOKEN in your .env file.');
            }

            return new Telegram($botToken);
        });
    }
}
