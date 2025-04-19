<?php

namespace HugPHP\Telegram;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;

/**
 * Telegram Bot API client for interacting with Telegram's API endpoints.
 *
 * This class provides a flexible and performant interface for sending messages,
 * retrieving updates, and other Telegram API operations. It supports configuration-driven
 * behavior, retry logic, and extensible endpoint handling.
 *
 * @see https://core.telegram.org/bots/api
 */
class Telegram
{
    /**
     * The Telegram Bot API token.
     *
     * @var string
     */
    protected $botToken;

    /**
     * The base URL for the Telegram API.
     *
     * @var string
     */
    protected $apiBaseUrl;

    /**
     * The HTTP client instance for making API requests.
     *
     * @var PendingRequest
     */
    protected $httpClient;

    /**
     * Telegram constructor.
     *
     * @param  string  $botToken  The Telegram Bot API token obtained from BotFather.
     * @param  string|null  $apiBaseUrl  The base URL for the Telegram API (optional, defaults to config or standard URL).
     * @param  PendingRequest|null  $httpClient  Custom HTTP client instance (optional, for dependency injection).
     *
     * @throws InvalidArgumentException If the bot token is empty or invalid.
     */
    public function __construct(string $botToken, ?string $apiBaseUrl = null, ?PendingRequest $httpClient = null)
    {
        if (empty($botToken)) {
            throw new InvalidArgumentException('Telegram bot token cannot be empty.');
        }

        $this->botToken = $botToken;
        $this->apiBaseUrl = $apiBaseUrl ?? config('telegram.api_base_url', 'https://api.telegram.org');
        $this->httpClient = $httpClient ?? $this->buildHttpClient();
    }

    /**
     * Send a message to a Telegram chat.
     *
     * @param  string  $chatId  The unique identifier for the target chat or username of the target channel (e.g., '@channelusername').
     * @param  string  $message  The message text to send.
     * @param  array  $options  Additional options for the message (e.g., parse_mode, reply_to_message_id).
     * @return array The API response as an associative array.
     *
     * @throws RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#sendmessage
     */
    public function sendMessage(string $chatId, string $message, array $options = []): array
    {
        $payload = array_merge([
            'chat_id' => $chatId,
            'text' => $message,
        ], $options);

        return $this->request('post', 'sendMessage', $payload);
    }

    /**
     * Retrieve updates for the bot.
     *
     * @param  array  $options  Optional parameters (e.g., offset, limit, timeout).
     * @return array The API response as an associative array.
     *
     * @throws RuntimeException If the API request fails or returns an error.
     *
     * @see https://core.telegram.org/bots/api#getupdates
     */
    public function getUpdates(array $options = []): array
    {
        return $this->request('get', 'getUpdates', $options);
    }

    /**
     * Make a request to the Telegram API.
     *
     * This method abstracts the HTTP request logic, handling retries, error checking,
     * and response parsing for all API endpoints.
     *
     * @param  string  $method  The HTTP method (get or post).
     * @param  string  $endpoint  The Telegram API endpoint (e.g., sendMessage, getUpdates).
     * @param  array  $payload  The request payload (query parameters for GET, body for POST).
     * @return array The API response as an associative array.
     *
     * @throws RuntimeException If the API request fails or returns an error.
     */
    protected function request(string $method, string $endpoint, array $payload = []): array
    {
        $url = "{$this->apiBaseUrl}/bot{$this->botToken}/{$endpoint}";

        $response = $method === 'get'
            ? $this->httpClient->get($url, $payload)
            : $this->httpClient->post($url, $payload);

        $data = $this->parseResponse($response);

        if (! $data['ok']) {
            throw new RuntimeException("Telegram API error: {$data['description']} (Code: {$data['error_code']})");
        }

        return $data;
    }

    /**
     * Parse the HTTP response and handle errors.
     *
     * @param  Response  $response  The HTTP response from the Telegram API.
     * @return array The parsed JSON response.
     *
     * @throws RuntimeException If the response is invalid or cannot be parsed.
     */
    protected function parseResponse(Response $response): array
    {
        if ($response->failed()) {
            throw new RuntimeException('Failed to connect to Telegram API: '.$response->status());
        }

        $data = $response->json();

        if (! is_array($data) || ! isset($data['ok'])) {
            throw new RuntimeException('Invalid Telegram API response.');
        }

        return $data;
    }

    /**
     * Build and configure the HTTP client.
     *
     * @return PendingRequest The configured HTTP client instance.
     */
    protected function buildHttpClient(): PendingRequest
    {
        return Http::withOptions([
            'timeout' => config('telegram.http_timeout', 10),
            'retry' => config('telegram.http_retries', 3),
            'retry_delay' => config('telegram.http_retry_delay', 500),
        ])->acceptJson();
    }

    /**
     * Get the bot token.
     *
     * @return string The Telegram bot token.
     */
    public function getBotToken(): string
    {
        return $this->botToken;
    }

    /**
     * Get the API base URL.
     *
     * @return string The Telegram API base URL.
     */
    public function getApiBaseUrl(): string
    {
        return $this->apiBaseUrl;
    }
}
