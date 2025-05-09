<?php

declare(strict_types=1);

namespace HugPHP\Telegram\Contracts;

use Illuminate\Http\UploadedFile;

/**
 * Interface for Telegram Bot API clients.
 *
 * Defines methods for interacting with Telegram's API endpoints, including sending messages,
 * media, locations, contacts, and managing webhooks. Implementations must provide configuration-driven
 * behavior, retry logic, and extensible endpoint handling.
 *
 * @see https://core.telegram.org/bots/api
 */
interface TelegramClientInterface
{
    /**
     * Get the Telegram Bot API token.
     *
     * @return string The bot token.
     */
    public function getBotToken(): string;

    /**
     * Get the base URL for the Telegram API.
     *
     * @return string The API base URL.
     */
    public function getApiBaseUrl(): string;

    /**
     * Send a message to a Telegram chat.
     *
     * @param  string  $chatId  The unique identifier for the target chat or username of the target channel (e.g., '@channelusername').
     * @param  string  $message  The message text to send.
     * @param  array<string, mixed>  $options  Additional options (e.g., parse_mode, reply_to_message_id, reply_markup).
     * @return array<string, mixed> The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#sendmessage
     */
    public function sendMessage(string $chatId, string $message, array $options = []): array;

    /**
     * Send a photo to a Telegram chat.
     *
     * @param  string  $chatId  The unique identifier for the target chat or username of the target channel.
     * @param  string|UploadedFile  $photo  The URL of the photo or an UploadedFile instance for local files.
     * @param  array<string, mixed>  $options  Additional options (e.g., caption, parse_mode, reply_markup).
     * @return array<string, mixed> The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#sendphoto
     */
    public function sendPhoto(string $chatId, $photo, array $options = []): array;

    /**
     * Send a video to a Telegram chat.
     *
     * @param  string  $chatId  The unique identifier for the target chat or username of the target channel.
     * @param  string|UploadedFile  $video  The URL of the video or an UploadedFile instance for local files.
     * @param  array<string, mixed>  $options  Additional options (e.g., caption, parse_mode, reply_markup).
     * @return array<string, mixed> The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#sendvideo
     */
    public function sendVideo(string $chatId, $video, array $options = []): array;

    /**
     * Send a document to a Telegram chat.
     *
     * @param  string  $chatId  The unique identifier for the target chat or username of the target channel.
     * @param  string|UploadedFile  $document  The URL of the document or an UploadedFile instance for local files.
     * @param  array<string, mixed>  $options  Additional options (e.g., caption, parse_mode, reply_markup).
     * @return array<string, mixed> The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#senddocument
     */
    public function sendDocument(string $chatId, $document, array $options = []): array;

    /**
     * Send a location to a Telegram chat.
     *
     * @param  string  $chatId  The unique identifier for the target chat or username of the target channel.
     * @param  float  $latitude  The latitude of the location.
     * @param  float  $longitude  The longitude of the location.
     * @param  array<string, mixed>  $options  Additional options (e.g., live_period, reply_markup).
     * @return array<string, mixed> The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#sendlocation
     */
    public function sendLocation(string $chatId, float $latitude, float $longitude, array $options = []): array;

    /**
     * Send a contact to a Telegram chat.
     *
     * @param  string  $chatId  The unique identifier for the target chat or username of the target channel.
     * @param  string  $phoneNumber  The contact's phone number.
     * @param  string  $firstName  The contact's first name.
     * @param  array<string, mixed>  $options  Additional options (e.g., last_name, vcard, reply_markup).
     * @return array<string, mixed> The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#sendcontact
     */
    public function sendContact(string $chatId, string $phoneNumber, string $firstName, array $options = []): array;

    /**
     * Set a webhook for receiving Telegram updates.
     *
     * @param  string  $url  The URL to receive webhook updates.
     * @param  array<string, mixed>  $options  Additional options (e.g., allowed_updates, max_connections).
     * @return array<string, mixed> The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#setwebhook
     */
    public function setWebhook(string $url, array $options = []): array;

    /**
     * Get information about the current webhook.
     *
     * @return array<string, mixed> The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#getwebhookinfo
     */
    public function getWebhookInfo(): array;

    /**
     * Delete the current webhook.
     *
     * @param  array<string, mixed>  $options  Additional options (e.g., drop_pending_updates).
     * @return array<string, mixed> The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#deletewebhook
     */
    public function deleteWebhook(array $options = []): array;

    /**
     * Retrieve updates for the bot.
     *
     * @param  array<string, mixed>  $options  Optional parameters (e.g., offset, limit, timeout).
     * @return array<string, mixed> The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#getupdates
     */
    public function getUpdates(array $options = []): array;
}
