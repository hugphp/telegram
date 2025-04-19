<?php

namespace HugPHP\Telegram\Contracts;

interface TelegramClientInterface
{
    /**
     * Get the bot token.
     *
     * @return string
     */
    public function getBotToken(): string;

    /**
     * Get the API base URL.
     *
     * @return string
     */
    public function getApiBaseUrl(): string;

    /**
     * Send a message to a Telegram chat.
     *
     * @param  string  $chatId
     * @param  string  $message
     * @param  array  $options
     * @return array
     */
    public function sendMessage(string $chatId, string $message, array $options = []): array;

    /**
     * Retrieve updates for the bot.
     *
     * @param  array  $options
     * @return array
     */
    public function getUpdates(array $options = []): array;
}
