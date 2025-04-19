<?php

namespace HugPHP\Telegram\Support;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;

/**
 * Base class for Telegram Bot API clients, providing core HTTP request logic.
 *
 * This class abstracts HTTP request handling, configuration, and error parsing
 * for Telegram API interactions, supporting retry logic and dependency injection.
 *
 * @see https://core.telegram.org/bots/api
 */
abstract class TelegramClientBase
{
    /**
     * The Telegram Bot API token.
     */
    protected string $botToken;

    /**
     * The base URL for the Telegram API.
     */
    protected string $apiBaseUrl;

    /**
     * The HTTP client instance for making API requests.
     */
    protected PendingRequest $httpClient;

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
     * Make a request to the Telegram API.
     *
     * This method abstracts the HTTP request logic, handling retries, error checking,
     * and response parsing for all API endpoints. Supports file uploads for multipart requests.
     *
     * @param  string  $method  The HTTP method (get or post).
     * @param  string  $endpoint  The Telegram API endpoint (e.g., sendMessage, getUpdates).
     * @param  array  $payload  The request payload (query parameters for GET, body for POST).
     * @param  ?array  $files  Files to upload (e.g., ['photo' => UploadedFile]).
     * @return array The API response as an associative array.
     *
     * @throws RuntimeException If the API request fails or returns an error.
     */
    protected function sendRequest(string $method, string $endpoint, array $payload = [], array $files = []): array
    {
        $url = "{$this->apiBaseUrl}/bot{$this->botToken}/{$endpoint}";

        if ($method === 'get') {
            $response = $this->httpClient->get($url, $payload);
        } else {
            if (! empty($files)) {
                $response = $this->httpClient->asMultipart();
                foreach ($payload as $key => $value) {
                    $response = $response->attach($key, $value);
                }
                foreach ($files as $key => $file) {
                    $response = $response->attach($key, $file->getContent(), $file->getClientOriginalName());
                }
                $response = $response->post($url);
            } else {
                $response = $this->httpClient->post($url, $payload);
            }
        }

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
