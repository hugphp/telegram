<?php

use HugPHP\Telegram\Telegram;
use Illuminate\Support\Facades\Http;

it('can send a message', function () {
    $telegram = new Telegram('fake_token');
    // Mock HTTP requests if needed
    expect(true)->toBeTrue(); // Replace with actual test
});

it('sends a message successfully', function () {
    Http::fake([
        'https://api.telegram.org/*' => Http::response(['ok' => true], 200),
    ]);

    $telegram = new Telegram('fake_token');
    $response = $telegram->sendMessage('12345', 'Test message');

    expect($response['ok'])->toBeTrue();
});