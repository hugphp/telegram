<?php

namespace HugPHP\Telegram;

use Illuminate\Support\Facades\Http;

class Telegram
{
    protected $botToken;

    public function __construct(string $botToken)
    {
        $this->botToken = $botToken;
    }

    public function sendMessage(string $chatId, string $message): array
    {
        $response = Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
        ]);

        return $response->json();
    }
}
