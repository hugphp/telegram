<?php

declare(strict_types=1);

namespace HugPHP\Telegram;

use HugPHP\Telegram\Contracts\TelegramClientInterface;
use HugPHP\Telegram\Support\TelegramClientBase;
use Illuminate\Http\UploadedFile;

/**
 * Telegram Bot API client for interacting with Telegram's API endpoints.
 *
 * This class provides a flexible and performant interface for sending messages,
 * media, locations, contacts, and managing webhooks, among other Telegram API operations.
 * It supports configuration-driven behavior, retry logic, and extensible endpoint handling.
 *
 * @see https://core.telegram.org/bots/api
 */
class Telegram extends TelegramClientBase implements TelegramClientInterface
{
    /**
     * Send a message to a Telegram chat.
     *
     * @param  string  $chatId  The unique identifier for the target chat or username of the target channel (e.g., '@channelusername').
     * @param  string  $message  The message text to send.
     * @param  array  $options  Additional options (e.g., parse_mode, reply_to_message_id, reply_markup).
     * @return array The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#sendmessage
     */
    public function sendMessage(string $chatId, string $message, array $options = []): array
    {
        $payload = array_merge([
            'chat_id' => $chatId,
            'text' => $message,
        ], $options);

        return $this->sendRequest('post', 'sendMessage', $payload);
    }

    /**
     * Send a photo to a Telegram chat.
     *
     * @param  string  $chatId  The unique identifier for the target chat or username of the target channel.
     * @param  string|UploadedFile  $photo  The URL of the photo or an UploadedFile instance for local files.
     * @param  array  $options  Additional options (e.g., caption, parse_mode, reply_markup).
     * @return array The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#sendphoto
     */
    public function sendPhoto(string $chatId, $photo, array $options = []): array
    {
        $payload = array_merge([
            'chat_id' => $chatId,
        ], $options);

        if ($photo instanceof UploadedFile) {
            return $this->sendRequest('post', 'sendPhoto', $payload, ['photo' => $photo]);
        }

        $payload['photo'] = $photo;

        return $this->sendRequest('post', 'sendPhoto', $payload);
    }

    /**
     * Send a video to a Telegram chat.
     *
     * @param  string  $chatId  The unique identifier for the target chat or username of the target channel.
     * @param  string|UploadedFile  $video  The URL of the video or an UploadedFile instance for local files.
     * @param  array  $options  Additional options (e.g., caption, parse_mode, reply_markup).
     * @return array The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#sendvideo
     */
    public function sendVideo(string $chatId, $video, array $options = []): array
    {
        $payload = array_merge([
            'chat_id' => $chatId,
        ], $options);

        if ($video instanceof UploadedFile) {
            return $this->sendRequest('post', 'sendVideo', $payload, ['video' => $video]);
        }

        $payload['video'] = $video;

        return $this->sendRequest('post', 'sendVideo', $payload);
    }

    /**
     * Send a document to a Telegram chat.
     *
     * @param  string  $chatId  The unique identifier for the target chat or username of the target channel.
     * @param  string|UploadedFile  $document  The URL of the document or an UploadedFile instance for local files.
     * @param  array  $options  Additional options (e.g., caption, parse_mode, reply_markup).
     * @return array The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#senddocument
     */
    public function sendDocument(string $chatId, $document, array $options = []): array
    {
        $payload = array_merge([
            'chat_id' => $chatId,
        ], $options);

        if ($document instanceof UploadedFile) {
            return $this->sendRequest('post', 'sendDocument', $payload, ['document' => $document]);
        }

        $payload['document'] = $document;

        return $this->sendRequest('post', 'sendDocument', $payload);
    }

    /**
     * Send a location to a Telegram chat.
     *
     * @param  string  $chatId  The unique identifier for the target chat or username of the target channel.
     * @param  float  $latitude  The latitude of the location.
     * @param  float  $longitude  The longitude of the location.
     * @param  array  $options  Additional options (e.g., live_period, reply_markup).
     * @return array The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#sendlocation
     */
    public function sendLocation(string $chatId, float $latitude, float $longitude, array $options = []): array
    {
        $payload = array_merge([
            'chat_id' => $chatId,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ], $options);

        return $this->sendRequest('post', 'sendLocation', $payload);
    }

    /**
     * Send a contact to a Telegram chat.
     *
     * @param  string  $chatId  The unique identifier for the target chat or username of the target channel.
     * @param  string  $phoneNumber  The contact's phone number.
     * @param  string  $firstName  The contact's first name.
     * @param  array  $options  Additional options (e.g., last_name, vcard, reply_markup).
     * @return array The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#sendcontact
     */
    public function sendContact(string $chatId, string $phoneNumber, string $firstName, array $options = []): array
    {
        $payload = array_merge([
            'chat_id' => $chatId,
            'phone_number' => $phoneNumber,
            'first_name' => $firstName,
        ], $options);

        return $this->sendRequest('post', 'sendContact', $payload);
    }

    /**
     * Set a webhook for receiving Telegram updates.
     *
     * @param  string  $url  The URL to receive webhook updates.
     * @param  array  $options  Additional options (e.g., allowed_updates, max_connections).
     * @return array The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#setwebhook
     */
    public function setWebhook(string $url, array $options = []): array
    {
        $payload = array_merge([
            'url' => $url,
        ], $options);

        return $this->sendRequest('post', 'setWebhook', $payload);
    }

    /**
     * Get information about the current webhook.
     *
     * @return array The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#getwebhookinfo
     */
    public function getWebhookInfo(): array
    {
        return $this->sendRequest('get', 'getWebhookInfo');
    }

    /**
     * Delete the current webhook.
     *
     * @param  array  $options  Additional options (e.g., drop_pending_updates).
     * @return array The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#deletewebhook
     */
    public function deleteWebhook(array $options = []): array
    {
        return $this->sendRequest('post', 'deleteWebhook', $options);
    }

    /**
     * Retrieve updates for the bot.
     *
     * @param  array  $options  Optional parameters (e.g., offset, limit, timeout).
     * @return array The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#getupdates
     */
    public function getUpdates(array $options = []): array
    {
        return $this->sendRequest('get', 'getUpdates', $options);
    }
}
