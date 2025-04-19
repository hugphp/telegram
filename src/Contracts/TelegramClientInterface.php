<?php

namespace HugPHP\Telegram\Contracts;

interface TelegramClientInterface
{
    /**
     * Get the bot token.
     */
    public function getBotToken(): string;

    /**
     * Get the API base URL.
     */
    public function getApiBaseUrl(): string;

    /**
     * Send a message to a Telegram chat.
     */
    public function sendMessage(string $chatId, string $message, array $options = []): array;

    public function sendPhoto(string $chatId, $photo, array $options = []): array;

    public function sendVideo(string $chatId, $video, array $options = []): array;

    public function sendLocation(string $chatId, float $latitude, float $longitude, array $options = []): array;

    public function sendContact(string $chatId, string $phoneNumber, string $firstName, array $options = []): array;

    public function setWebhook(string $url, array $options = []): array;

    public function getWebhookInfo(): array;

    public function deleteWebhook(array $options = []): array;

    public function sendDocument(string $chatId, $document, array $options = []): array;

    /**
     * Retrieve updates for the bot.
     */
    public function getUpdates(array $options = []): array;
}
