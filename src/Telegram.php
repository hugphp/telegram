<?php

namespace HugPHP\Telegram;

use HugPHP\Telegram\Contracts\TelegramClientInterface;
use HugPHP\Telegram\Support\TelegramClientBase;

/**
 * Telegram Bot API client for interacting with Telegram's API endpoints.
 *
 * This class provides a flexible and performant interface for sending messages,
 * retrieving updates, and other Telegram API operations. It supports configuration-driven
 * behavior, retry logic, and extensible endpoint handling.
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
     * @param  array  $options  Additional options for the message (e.g., parse_mode, reply_to_message_id).
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
