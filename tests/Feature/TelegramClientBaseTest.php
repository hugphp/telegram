<?php

use HugPHP\Telegram\Support\TelegramClientBase;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::preventStrayRequests();
    config([
        'telegram.api_base_url' => 'https://api.telegram.org',
        'telegram.http_timeout' => 15,
        'telegram.http_retries' => 2,
        'telegram.http_retry_delay' => 1000,
    ]);
});

test('throws an exception for empty bot token', function () {
    expect(fn () => new class('') extends TelegramClientBase {})
        ->toThrow(\InvalidArgumentException::class, 'Telegram bot token cannot be empty.');
});

test('gets bot token', function () {
    $telegram = new class('fake_token') extends TelegramClientBase {};
    expect($telegram->getBotToken())->toBe('fake_token');
});

test('gets API base URL', function () {
    $telegram = new class('fake_token', 'https://custom.api') extends TelegramClientBase {};
    expect($telegram->getApiBaseUrl())->toBe('https://custom.api');
});

test('uses default API base URL from config', function () {
    config(['telegram.api_base_url' => 'https://default.api']);
    $telegram = new class('fake_token') extends TelegramClientBase {};
    expect($telegram->getApiBaseUrl())->toBe('https://default.api');
});

test('builds HTTP client with config options', function () {
    $telegram = new class('fake_token') extends TelegramClientBase {};
    $reflection = new \ReflectionClass($telegram);
    $method = $reflection->getMethod('buildHttpClient');
    $method->setAccessible(true);
    $httpClient = $method->invoke($telegram);

    expect($httpClient)->toBeInstanceOf(PendingRequest::class);
});
