<?php

declare(strict_types=1);

namespace HugPHP\Telegram\Support;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;

/**
 * Base class for Telegram Bot API clients, providing core HTTP request logic.
 *
 * This abstract class handles HTTP request execution, configuration management, and response parsing
 * for Telegram API interactions. It supports retry logic, dependency injection, and file uploads
 * for multipart requests, serving as a foundation for concrete Telegram API clients.
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
     * Constructs a new Telegram client instance.
     *
     * Initializes the client with a bot token, optional API base URL, and optional HTTP client.
     * If no API base URL is provided, it defaults to the configuration value or the standard Telegram API URL.
     * If no HTTP client is provided, a new client is built with configured options.
     *
     * @param  string  $botToken  The Telegram Bot API token obtained from BotFather.
     * @param  string|null  $apiBaseUrl  The base URL for the Telegram API (optional, defaults to config or 'https://api.telegram.org').
     * @param  PendingRequest|null  $httpClient  Custom HTTP client instance (optional, for dependency injection).
     *
     * @throws \InvalidArgumentException If the bot token is empty.
     */
    public function __construct(string $botToken, ?string $apiBaseUrl = null, ?PendingRequest $httpClient = null)
    {
        if ($botToken === '' || $botToken === '0') {
            throw new InvalidArgumentException('Telegram bot token cannot be empty.');
        }

        $this->botToken = $botToken;
        $configApiBaseUrl = config('telegram.api_base_url', 'https://api.telegram.org');
        if (! is_string($configApiBaseUrl)) {
            throw new InvalidArgumentException('Telegram API base URL must be a string.');
        }
        $this->apiBaseUrl = $apiBaseUrl ?? $configApiBaseUrl;
        $this->httpClient = $httpClient ?? $this->buildHttpClient();
    }

    /**
     * Builds and configures the HTTP client for API requests.
     *
     * Creates a new HTTP client instance with configuration-driven options for timeout,
     * retries, and retry delay, and sets it to accept JSON responses.
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
     * Makes a request to the Telegram API.
     *
     * Handles HTTP request execution, including GET and POST methods, with support for
     * query parameters, JSON payloads, and multipart file uploads. Parses the response
     * and throws exceptions for errors.
     *
     * @param  string  $method  The HTTP method ('get' or 'post').
     * @param  string  $endpoint  The Telegram API endpoint (e.g., 'sendMessage', 'getUpdates').
     * @param  array<string, string|array<string, mixed>>  $payload  The request payload (query parameters for GET, body for POST).
     * @param  array<string, \Illuminate\Http\UploadedFile>  $files  Files to upload (e.g., ['photo' => UploadedFile]), defaults to empty array.
     * @return array<string, mixed> The API response as an associative array.
     *
     * @throws \RuntimeException If the API request fails or returns an error.
     */
    protected function sendRequest(string $method, string $endpoint, array $payload = [], array $files = []): array
    {
        $url = "{$this->apiBaseUrl}/bot{$this->botToken}/{$endpoint}";

        if ($method === 'get') {
            $response = $this->httpClient->get($url, $payload);
        } elseif ($files !== []) {
            $response = $this->httpClient->asMultipart();
            foreach ($payload as $key => $value) {
                if (is_array($value)) {
                    $contents = json_encode($value);
                    if ($contents === false) {
                        throw new InvalidArgumentException("Failed to JSON-encode payload value for key '{$key}'.");
                    }
                } else {
                    $contents = (string) $value;
                }
                $response = $response->attach($key, $contents);
            }
            foreach ($files as $key => $file) {
                $response = $response->attach($key, $file->getContent(), $file->getClientOriginalName());
            }
            $response = $response->post($url);
        } else {
            $response = $this->httpClient->post($url, $payload);
        }

        $data = $this->parseResponse($response);

        if (! $data['ok']) {
            $description = isset($data['description']) && is_string($data['description']) ? $data['description'] : 'Unknown error';
            $errorCode = isset($data['error_code']) && is_int($data['error_code']) ? $data['error_code'] : 0;
            throw new RuntimeException("Telegram API error: {$description} (Code: {$errorCode})");
        }

        return $data;
    }

    /**
     * Parses the HTTP response and handles errors.
     *
     * Extracts the JSON response from the HTTP response and validates its structure.
     * Throws an exception if the response is invalid or indicates a failure.
     *
     * @param  Response  $response  The HTTP response from the Telegram API.
     * @return array<string, mixed> The parsed JSON response as an associative array.
     *
     * @throws \RuntimeException If the response is invalid or cannot be parsed.
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

        // Ensure description and error_code are properly typed for error responses
        if (! $data['ok']) {
            $data['description'] = isset($data['description']) && is_string($data['description']) ? $data['description'] : 'Unknown error';
            $data['error_code'] = isset($data['error_code']) && is_int($data['error_code']) ? $data['error_code'] : 0;
        }

        return $data;
    }

    /**
     * Gets the Telegram Bot API token.
     *
     * @return string The bot token.
     */
    public function getBotToken(): string
    {
        return $this->botToken;
    }

    /**
     * Gets the base URL for the Telegram API.
     *
     * @return string The API base URL.
     */
    public function getApiBaseUrl(): string
    {
        return $this->apiBaseUrl;
    }
}
