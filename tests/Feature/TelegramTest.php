<?php

use HugPHP\Telegram\Telegram;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    // Fake HTTP requests to prevent real API calls
    Http::preventStrayRequests();
    // Set up temporary storage disk for file uploads
    Storage::fake('local');
    // Set default configuration values
    config([
        'telegram.api_base_url' => 'https://api.telegram.org',
        'telegram.http_timeout' => 10,
        'telegram.http_retries' => 3,
        'telegram.http_retry_delay' => 500,
    ]);
});

test('throws an exception for empty bot token', function (): void {
    expect(fn (): Telegram => new Telegram(''))->toThrow(\InvalidArgumentException::class, 'Telegram bot token cannot be empty.');
});

test('sends a message successfully', function (): void {
    Http::fake([
        'https://api.telegram.org/botfake_token/sendMessage' => Http::response([
            'ok' => true,
            'result' => [
                'message_id' => 123,
                'chat' => ['id' => '682299441'],
                'text' => 'Test message',
            ],
        ], 200),
    ]);

    $telegram = new Telegram('fake_token');
    $response = $telegram->sendMessage('682299441', 'Test message', ['parse_mode' => 'HTML']);

    expect($response['ok'])->toBeTrue();
    expect($response['result']['message_id'])->toBe(123);
});

// test('throws an exception for failed message send', function () {
//     Http::fake([
//         'https://api.telegram.org/botfake_token/sendMessage' => Http::response([
//             'ok' => false,
//             'error_code' => 400,
//             'description' => 'Bad Request: chat not found',
//         ], 400),
//     ]);

//     $telegram = new Telegram('fake_token');
//     expect(fn () => $telegram->sendMessage('invalid_chat_id', 'Test message'))
//         ->toThrow(\RuntimeException::class, 'Telegram API error: Bad Request: chat not found (Code: 400)');
// });

test('sends a photo with URL successfully', function (): void {
    Http::fake([
        'https://api.telegram.org/botfake_token/sendPhoto' => Http::response([
            'ok' => true,
            'result' => [
                'message_id' => 124,
                'chat' => ['id' => '682299441'],
                'photo' => [],
            ],
        ], 200),
    ]);

    $telegram = new Telegram('fake_token');
    $response = $telegram->sendPhoto('682299441', 'https://example.com/photo.jpg', ['caption' => 'Test photo']);

    expect($response['ok'])->toBeTrue();
    expect($response['result']['message_id'])->toBe(124);
});

test('sends a photo with UploadedFile successfully', function (): void {
    Http::fake([
        'https://api.telegram.org/botfake_token/sendPhoto' => Http::response([
            'ok' => true,
            'result' => [
                'message_id' => 124,
                'chat' => ['id' => '682299441'],
                'photo' => [],
            ],
        ], 200),
    ]);

    $file = UploadedFile::fake()->image('photo.jpg');
    $telegram = new Telegram('fake_token');
    $response = $telegram->sendPhoto('682299441', $file, ['caption' => 'Test photo']);

    expect($response['ok'])->toBeTrue();
    expect($response['result']['message_id'])->toBe(124);
});

test('sends a video with URL successfully', function (): void {
    Http::fake([
        'https://api.telegram.org/botfake_token/sendVideo' => Http::response([
            'ok' => true,
            'result' => [
                'message_id' => 125,
                'chat' => ['id' => '682299441'],
                'video' => [],
            ],
        ], 200),
    ]);

    $telegram = new Telegram('fake_token');
    $response = $telegram->sendVideo('682299441', 'https://example.com/video.mp4', ['caption' => 'Test video']);

    expect($response['ok'])->toBeTrue();
    expect($response['result']['message_id'])->toBe(125);
});

// test('sends a video with UploadedFile successfully', function () {
//     Http::fake([
//         'https://api.telegram.org/botfake_token/sendVideo' => Http::response([
//             'ok' => true,
//             'result' => [
//                 'message_id' => 125,
//                 'chat' => ['id' => '682299441'],
//                 'video' => [],
//             ],
//         ], 200),
//     ]);

//     $file = UploadedFile::fake()->create('video.mp4', 1000, 'video/mp4');
//     $telegram = new Telegram('fake_token');
//     $response = $telegram->sendVideo('682299441', $file, ['caption' => 'Test video']);

//     expect($response['ok'])->toBeTrue();
//     expect($response['result']['message_id'])->toBe(125);
// });

test('sends a document with URL successfully', function (): void {
    Http::fake([
        'https://api.telegram.org/botfake_token/sendDocument' => Http::response([
            'ok' => true,
            'result' => [
                'message_id' => 126,
                'chat' => ['id' => '682299441'],
                'document' => [],
            ],
        ], 200),
    ]);

    $telegram = new Telegram('fake_token');
    $response = $telegram->sendDocument('682299441', 'https://example.com/document.pdf', ['caption' => 'Test document']);

    expect($response['ok'])->toBeTrue();
    expect($response['result']['message_id'])->toBe(126);
});

// test('sends a document with UploadedFile successfully', function () {
//     Http::fake([
//         'https://api.telegram.org/botfake_token/sendDocument' => Http::response([
//             'ok' => true,
//             'result' => [
//                 'message_id' => 126,
//                 'chat' => ['id' => '682299441'],
//                 'document' => [],
//             ],
//         ], 200),
//     ]);

//     $file = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');
//     $telegram = new Telegram('fake_token');
//     $response = $telegram->sendDocument('682299441', $file, ['caption' => 'Test document']);

//     expect($response['ok'])->toBeTrue();
//     expect($response['result']['message_id'])->toBe(126);
// });

test('sends a location successfully', function (): void {
    Http::fake([
        'https://api.telegram.org/botfake_token/sendLocation' => Http::response([
            'ok' => true,
            'result' => [
                'message_id' => 127,
                'chat' => ['id' => '682299441'],
                'location' => [],
            ],
        ], 200),
    ]);

    $telegram = new Telegram('fake_token');
    $response = $telegram->sendLocation('682299441', 51.5074, -0.1278, ['live_period' => 3600]);

    expect($response['ok'])->toBeTrue();
    expect($response['result']['message_id'])->toBe(127);
});

test('sends a contact successfully', function (): void {
    Http::fake([
        'https://api.telegram.org/botfake_token/sendContact' => Http::response([
            'ok' => true,
            'result' => [
                'message_id' => 128,
                'chat' => ['id' => '682299441'],
                'contact' => [],
            ],
        ], 200),
    ]);

    $telegram = new Telegram('fake_token');
    $response = $telegram->sendContact('682299441', '+1234567890', 'John Doe', ['last_name' => 'Smith']);

    expect($response['ok'])->toBeTrue();
    expect($response['result']['message_id'])->toBe(128);
});

test('sets a webhook successfully', function (): void {
    Http::fake([
        'https://api.telegram.org/botfake_token/setWebhook' => Http::response([
            'ok' => true,
            'result' => true,
            'description' => 'Webhook was set',
        ], 200),
    ]);

    $telegram = new Telegram('fake_token');
    $response = $telegram->setWebhook('https://example.com/webhook', ['max_connections' => 40]);

    expect($response['ok'])->toBeTrue();
    expect($response['result'])->toBeTrue();
});

test('gets webhook info successfully', function (): void {
    Http::fake([
        'https://api.telegram.org/botfake_token/getWebhookInfo' => Http::response([
            'ok' => true,
            'result' => [
                'url' => 'https://example.com/webhook',
                'has_custom_certificate' => false,
            ],
        ], 200),
    ]);

    $telegram = new Telegram('fake_token');
    $response = $telegram->getWebhookInfo();

    expect($response['ok'])->toBeTrue();
    expect($response['result']['url'])->toBe('https://example.com/webhook');
});

test('deletes webhook successfully', function (): void {
    Http::fake([
        'https://api.telegram.org/botfake_token/deleteWebhook' => Http::response([
            'ok' => true,
            'result' => true,
            'description' => 'Webhook was deleted',
        ], 200),
    ]);

    $telegram = new Telegram('fake_token');
    $response = $telegram->deleteWebhook(['drop_pending_updates' => true]);

    expect($response['ok'])->toBeTrue();
    expect($response['result'])->toBeTrue();
});

test('retrieves updates successfully', function (): void {
    Http::fake([
        'https://api.telegram.org/botfake_token/getUpdates*' => Http::response([
            'ok' => true,
            'result' => [
                [
                    'update_id' => 123456,
                    'message' => [
                        'chat' => ['id' => '682299441'],
                        'text' => 'Test',
                    ],
                ],
            ],
        ], 200),
    ]);

    $telegram = new Telegram('fake_token');
    $response = $telegram->getUpdates(['limit' => 10]);

    expect($response['ok'])->toBeTrue();
    expect($response['result'])->toBeArray();
});

test('throws an exception for invalid API response', function (): void {
    Http::fake([
        'https://api.telegram.org/botfake_token/sendMessage' => Http::response(['invalid' => true], 200),
    ]);

    $telegram = new Telegram('fake_token');
    expect(fn (): array => $telegram->sendMessage('682299441', 'Test message'))
        ->toThrow(\RuntimeException::class, 'Invalid Telegram API response.');
});

test('throws an exception for HTTP failure', function (): void {
    Http::fake([
        'https://api.telegram.org/botfake_token/sendMessage' => Http::response([], 500),
    ]);

    $telegram = new Telegram('fake_token');
    expect(fn (): array => $telegram->sendMessage('682299441', 'Test message'))
        ->toThrow(\RuntimeException::class, 'Failed to connect to Telegram API: 500');
});

test('uses custom API base URL', function (): void {
    Http::fake([
        'https://custom.telegram.api/botfake_token/getUpdates' => Http::response([
            'ok' => true,
            'result' => [],
        ], 200),
    ]);

    $telegram = new Telegram('fake_token', 'https://custom.telegram.api');
    $response = $telegram->getUpdates();

    expect($response['ok'])->toBeTrue();
});

test('uses custom HTTP client', function (): void {
    Http::fake([
        'https://api.telegram.org/botfake_token/getUpdates' => Http::response([
            'ok' => true,
            'result' => [],
        ], 200),
    ]);

    $httpClient = Http::withOptions(['timeout' => 5]);
    $telegram = new Telegram('fake_token', null, $httpClient);
    $response = $telegram->getUpdates();

    expect($response['ok'])->toBeTrue();
});

test('gets bot token', function (): void {
    $telegram = new Telegram('fake_token');
    expect($telegram->getBotToken())->toBe('fake_token');
});

test('gets API base URL', function (): void {
    $telegram = new Telegram('fake_token', 'https://custom.api');
    expect($telegram->getApiBaseUrl())->toBe('https://custom.api');
});

test('uses default API base URL from config', function (): void {
    config(['telegram.api_base_url' => 'https://default.api']);
    $telegram = new Telegram('fake_token');
    expect($telegram->getApiBaseUrl())->toBe('https://default.api');
});
