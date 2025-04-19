<?php

return [
    /**
     * The Telegram Bot API token obtained from BotFather.
     */
    'bot_token' => env('TELEGRAM_BOT_TOKEN', ''),

    /**
     * The base URL for the Telegram API.
     */
    'api_base_url' => env('TELEGRAM_API_BASE_URL', 'https://api.telegram.org'),

    /**
     * HTTP client timeout in seconds.
     */
    'http_timeout' => env('TELEGRAM_HTTP_TIMEOUT', 10),

    /**
     * Number of retries for failed HTTP requests.
     */
    'http_retries' => env('TELEGRAM_HTTP_RETRIES', 3),

    /**
     * Delay between retries in milliseconds.
     */
    'http_retry_delay' => env('TELEGRAM_HTTP_RETRY_DELAY', 500),

    /**
     * Default chat ID for sending messages (optional).
     */
    'default_chat_id' => env('TELEGRAM_DEFAULT_CHAT_ID', ''),
];
