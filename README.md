<p align="center">
    <img src="https://raw.githubusercontent.com/hugphp/telegram/master/docs/example.png" height="300" alt="Hugphp Telegram">
    <p align="center">
        <a href="https://github.com/hugphp/telegram/actions"><img alt="GitHub Workflow Status (master)" src="https://github.com/hugphp/telegram/actions/workflows/tests.yml/badge.svg"></a>
        <a href="https://packagist.org/packages/hugphp/telegram"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/hugphp/telegram"></a>
        <a href="https://packagist.org/packages/hugphp/telegram"><img alt="Latest Version" src="https://img.shields.io/packagist/v/hugphp/telegram"></a>
        <a href="https://packagist.org/packages/hugphp/telegram"><img alt="License" src="https://img.shields.io/packagist/l/hugphp/telegram"></a>
    </p>
</p>

------
# HugPHP Telegram

A robust and type-safe Laravel package for interacting with the [Telegram Bot API](https://core.telegram.org/bots/api). This package provides a simple, performant, and flexible interface for sending messages, media, locations, contacts, and managing webhooks in your Laravel applications.

## Features
- **Type-Safe**: Built with strict typing and validated by PHPStan for 100% type safety.
- **Easy Integration**: Seamlessly integrates with Laravel’s HTTP client and configuration system.
- **Comprehensive API Support**: Send messages, photos, videos, documents, locations, contacts, and manage webhooks.
- **Retry Logic**: Configurable retries and timeouts for reliable API communication.
- **Tested**: >83% test coverage with Pest, ensuring reliability and stability.

### Feature Roadmap

| Feature | Method | Status |
| --- | --- | --- |
| Send Message | `sendMessage` | ✅ Completed |
| Send Photo | `sendPhoto` | ✅ Completed |
| Send Video | `sendVideo` | ✅ Completed |
| Send Document | `sendDocument` | ✅ Completed |
| Send Location | `sendLocation` | ✅ Completed |
| Send Contact | `sendContact` | ✅ Completed |
| Set Webhook | `setWebhook` | ✅ Completed |
| Get Webhook Info | `getWebhookInfo` | ✅ Completed |
| Delete Webhook | `deleteWebhook` | ✅ Completed |
| Get Updates | `getUpdates` | ✅ Completed |
| Handle Callback Query | `handleCallbackQuery` | ☐ Pending |
| File Uploads | `fileUploads` | ☐ Pending |
| Send Notification | `sendNotification` | ☐ Pending |

## Installation

Install the package via Composer:

```bash
composer require hugphp/telegram
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="HugPHP\Telegram\TelegramServiceProvider"
```

This creates a `config/telegram.php` file for configuring your Telegram bot settings.

## Configuration

Edit `config/telegram.php` to set your bot token and other settings:

```php
return [
    'bot_token' => env('TELEGRAM_BOT_TOKEN', 'your-bot-token'),
    'default_chat_id' => env('TELEGRAM_DEFAULT_CHAT_ID', '68*****41'),
    'api_base_url' => env('TELEGRAM_API_BASE_URL', 'https://api.telegram.org'),
    'webhook_url' => env('TELEGRAM_WEBHOOK_URL', 'https://your-app.com/telegram/webhook'),
    'webhook_max_connections' => env('TELEGRAM_WEBHOOK_MAX_CONNECTIONS', 40),
    'webhook_allowed_updates' => env('TELEGRAM_WEBHOOK_ALLOWED_UPDATES', []),
    'http_timeout' => env('TELEGRAM_HTTP_TIMEOUT', 10),
    'http_retries' => env('TELEGRAM_HTTP_RETRIES', 3),
    'http_retry_delay' => env('TELEGRAM_HTTP_RETRY_DELAY', 500),
];
```

Add the following to your `.env` file:

```env
TELEGRAM_BOT_TOKEN=your-bot-token-from-botfather
TELEGRAM_DEFAULT_CHAT_ID=68*****41
TELEGRAM_WEBHOOK_URL=https://your-app.com/telegram/webhook
```

## Usage

The package provides a `Telegram` facade for easy interaction with the Telegram Bot API. Below are examples for each supported method, based on tested functionality.

### Sending a Message

Send a formatted HTML message with an inline keyboard:

```php
use HugPHP\Telegram\Facades\Telegram;

$chatId = config('telegram.default_chat_id', '68*****41');
$message = '<b>Hello</b> from <i>HugPHP</i> <a href="https://hugphp.com">Telegram</a>!';
$response = Telegram::sendMessage($chatId, $message, [
    'parse_mode' => 'HTML',
    'reply_markup' => json_encode([
        'inline_keyboard' => [
            [['text' => 'Visit Website', 'url' => 'https://github.com/hugphp/telegram']],
            [['text' => 'Open MinApp', 'web_app' => ['url' => 'https://github.com/hugphp/telegram']]],
        ],
    ]),
]);
```

**Example Response**:
```json
{
  "ok": true,
  "result": {
    "message_id": 34,
    "from": {
      "id": 78*****897,
      "is_bot": true,
      "first_name": "hugphp telegram",
      "username": "HugPHPTelegramBot"
    },
    "chat": {
      "id": 68*****41,
      "first_name": "Mike",
      "username": "micheal_ataklt",
      "type": "private"
    },
    "date": 1745171806,
    "text": "Hello from HugPHP Telegram!",
    "entities": [
      {"offset": 0, "length": 5, "type": "bold"},
      {"offset": 11, "length": 6, "type": "italic"},
      {"offset": 18, "length": 8, "type": "text_link", "url": "https://hugphp.com/"}
    ],
    "reply_markup": {
      "inline_keyboard": [
        [{"text": "Visit Website", "url": "https://github.com/hugphp/telegram"}],
        [{"text": "Open MinApp", "web_app": {"url": "https://github.com/hugphp/telegram"}}]
      ]
    }
  }
}
```

### Sending a Photo

Send a photo with a caption:

```php
$response = Telegram::sendPhoto($chatId, 'https://tailwindcss.com/plus-assets/img/component-images/dark-project-app-screenshot.png', [
    'caption' => 'Test photo',
]);
```

**Example Response**:
```json
{
  "ok": true,
  "result": {
    "message_id": 35,
    "from": {
      "id": 78*****897,
      "is_bot": true,
      "first_name": "hugphp telegram",
      "username": "HugPHPTelegramBot"
    },
    "chat": {
      "id": 68*****41,
      "first_name": "Mike",
      "username": "micheal_ataklt",
      "type": "private"
    },
    "date": 1745171806,
    "photo": [
      {
        "file_id": "AgACAgQAAxkDAAMPaAPvxocvYpM5Bxm1xZJVv-R3-XYAApq3MRux3V1S-1eP43y186gBAAMCAANzAAM2BA",
        "file_unique_id": "AQADmrcxG7HdXVJ4",
        "file_size": 843,
        "width": 90,
        "height": 53
      },
      ...
    ],
    "caption": "Test photo"
  }
}
```

### Sending a Video

Send a video with a caption:

```php
$response = Telegram::sendVideo($chatId, 'https://cdn.pixabay.com/video/2025/03/28/268290_large.mp4', [
    'caption' => 'Test video',
]);
```

**Example Response**:
```json
{
  "ok": true,
  "result": {
    "message_id": 36,
    "from": {
      "id": 78*****897,
      "is_bot": true,
      "first_name": "hugphp telegram",
      "username": "HugPHPTelegramBot"
    },
    "chat": {
      "id": 68*****41,
      "first_name": "Mike",
      "username": "micheal_ataklt",
      "type": "private"
    },
    "date": 1745171807,
    "video": {
      "duration": 21,
      "width": 1920,
      "height": 1080,
      "file_name": "268290_large.mp4",
      "mime_type": "video/mp4",
      "file_id": "BAACAgQAAxkDAAMUaAUz0xMkTfAt6U5fpThEhdoNLWYAAqYHAAKpO_xTHX5XZpe5yrI2BA",
      "file_unique_id": "AgADpgcAAqk7_FM",
      "file_size": 12950075
    },
    "caption": "Test video"
  }
}
```

### Sending a Document

Send a document with a caption:

```php
$response = Telegram::sendDocument($chatId, 'https://www.fsa.usda.gov/Internet/FSA_File/tech_assist.pdf', [
    'caption' => 'Test document',
]);
```

**Example Response**:
```json
{
  "ok": true,
  "result": {
    "message_id": 37,
    "from": {
      "id": 78*****897,
      "is_bot": true,
      "first_name": "hugphp telegram",
      "username": "HugPHPTelegramBot"
    },
    "chat": {
      "id": 68*****41,
      "first_name": "Mike",
      "username": "micheal_ataklt",
      "type": "private"
    },
    "date": 1745171808,
    "document": {
      "file_name": "tech_assist.pdf",
      "mime_type": "application/pdf",
      "file_id": "BQACAgQAAxkDAAMQaAPwZ9Q1XR_bK67ZeHQTnSdtDDMAAuEHAAIU78RTo-YwBHZMf-s2BA",
      "file_unique_id": "AgAD4QcAAhTvxFM",
      "file_size": 60232
    },
    "caption": "Test document"
  }
}
```

### Sending a Location

Send a live location:

```php
$response = Telegram::sendLocation($chatId, 51.5074, -0.1278, [
    'live_period' => 3600,
]);
```

**Example Response**:
```json
{
  "ok": true,
  "result": {
    "message_id": 38,
    "from": {
      "id": 78*****897,
      "is_bot": true,
      "first_name": "hugphp telegram",
      "username": "HugPHPTelegramBot"
    },
    "chat": {
      "id": 68*****41,
      "first_name": "Mike",
      "username": "micheal_ataklt",
      "type": "private"
    },
    "date": 1745171808,
    "location": {
      "latitude": 51.507394,
      "longitude": -0.127813,
      "live_period": 3600
    }
  }
}
```

### Sending a Contact

Send a contact with first and last name:

```php
$response = Telegram::sendContact($chatId, '+1234567890', 'John Doe', [
    'last_name' => 'Smith',
]);
```

**Example Response**:
```json
{
  "ok": true,
  "result": {
    "message_id": 39,
    "from": {
      "id": 78*****897,
      "is_bot": true,
      "first_name": "hugphp telegram",
      "username": "HugPHPTelegramBot"
    },
    "chat": {
      "id": 68*****41,
      "first_name": "Mike",
      "username": "micheal_ataklt",
      "type": "private"
    },
    "date": 1745171809,
    "contact": {
      "phone_number": "+1234567890",
      "first_name": "John Doe",
      "last_name": "Smith"
    }
  }
}
```

### Setting a Webhook

Set a webhook for receiving updates:

```php
$webhookUrl = config('telegram.webhook_url', '');
$response = Telegram::setWebhook($webhookUrl, [
    'max_connections' => config('telegram.webhook_max_connections', 40),
    'allowed_updates' => config('telegram.webhook_allowed_updates', []),
]);
```

**Example Response**:
```json
{
  "ok": true,
  "result": true,
  "description": "Webhook was set"
}
```

### Getting Webhook Info

Retrieve webhook information:

```php
$response = Telegram::getWebhookInfo();
```

**Example Response**:
```json
{
  "ok": true,
  "result": {
    "url": "https://your-app.com/telegram/webhook",
    "has_custom_certificate": false,
    "pending_update_count": 4,
    "max_connections": 40,
    "ip_address": "3.18.7.81"
  }
}
```

### Deleting a Webhook

Delete the current webhook:

```php
$response = Telegram::deleteWebhook();
```

**Example Response**:
```json
{
  "ok": true,
  "result": true,
  "description": "Webhook was deleted"
}
```

### Getting Updates

Retrieve bot updates:

```php
$response = Telegram::getUpdates([
    'limit' => 10,
]);
```

**Example Response**:
```json
{
  "ok": true,
  "result": [
    {
      "update_id": 261265780,
      "message": {
        "message_id": 18,
        "from": {
          "id": 68*****41,
          "is_bot": false,
          "first_name": "Mike",
          "username": "micheal_ataklt",
          "language_code": "en"
        },
        "chat": {
          "id": 68*****41,
          "first_name": "Mike",
          "username": "micheal_ataklt",
          "type": "private"
        },
        "date": 1745143447,
        "location": {
          "latitude": 8.891756,
          "longitude": 38.834948,
          "live_period": 2147483647
        }
      }
    },
    ...
  ]
}
```

## Testing

The package includes a comprehensive test suite with 100% coverage using Pest. You can test all functionalities in your Laravel project by accessing the `/validate-all-functionalities` route:

```php
<?php

use HugPHP\Telegram\Facades\Telegram;
use Illuminate\Support\Facades\Route;

// -----

Route::get('/validate-all-functionalities', function (): array {
    $chatId = config('telegram.default_chat_id', '68*****41');
    $webhookUrl = config('telegram.webhook_url', '');
    $results = [];

    // Test sendMessage
    try {
        $messageInHtml = '<b>Hello</b> from <i>HugPHP</i> <a href="https://hugphp.com">Telegram</a>!';
        $results['sendMessage'] = [
            'status' => 'success',
            'response' => Telegram::sendMessage($chatId, $messageInHtml, [
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => 'Visit Website', 'url' => 'https://github.com/hugphp/telegram']],
                        [['text' => 'Open MinApp', 'web_app' => ['url' => 'https://github.com/hugphp/telegram']]],
                    ],
                ]),
            ]),
        ];
    } catch (\RuntimeException $e) {
        $results['sendMessage'] = ['status' => 'error', 'error' => $e->getMessage()];
    }

    // Test sendPhoto
    try {
        $results['sendPhoto'] = [
            'status' => 'success',
            'response' => Telegram::sendPhoto($chatId, 'https://tailwindcss.com/plus-assets/img/component-images/dark-project-app-screenshot.png', [
                'caption' => 'Test photo',
            ]),
        ];
    } catch (\RuntimeException $e) {
        $results['sendPhoto'] = ['status' => 'error', 'error' => $e->getMessage()];
    }

    // Test sendVideo
    try {
        $results['sendVideo'] = [
            'status' => 'success',
            'response' => Telegram::sendVideo($chatId, 'https://cdn.pixabay.com/video/2025/03/28/268290_large.mp4', [
                'caption' => 'Test video',
            ]),
        ];
    } catch (\RuntimeException $e) {
        $results['sendVideo'] = ['status' => 'error', 'error' => $e->getMessage()];
    }

    // Test sendDocument
    try {
        $results['sendDocument'] = [
            'status' => 'success',
            'response' => Telegram::sendDocument($chatId, 'https://www.fsa.usda.gov/Internet/FSA_File/tech_assist.pdf', [
                'caption' => 'Test document',
            ]),
        ];
    } catch (\RuntimeException $e) {
        $results['sendDocument'] = ['status' => 'error', 'error' => $e->getMessage()];
    }

    // Test sendLocation
    try {
        $results['sendLocation'] = [
            'status' => 'success',
            'response' => Telegram::sendLocation($chatId, 51.5074, -0.1278, [
                'live_period' => 3600,
            ]),
        ];
    } catch (\RuntimeException $e) {
        $results['sendLocation'] = ['status' => 'error', 'error' => $e->getMessage()];
    }

    // Test sendContact
    try {
        $results['sendContact'] = [
            'status' => 'success',
            'response' => Telegram::sendContact($chatId, '+1234567890', 'John Doe', [
                'last_name' => 'Smith',
            ]),
        ];
    } catch (\RuntimeException $e) {
        $results['sendContact'] = ['status' => 'error', 'error' => $e->getMessage()];
    }

    // Test setWebhook
    try {
        $results['setWebhook'] = [
            'status' => 'success',
            'response' => Telegram::setWebhook($webhookUrl, [
                'max_connections' => config('telegram.webhook_max_connections', 40),
                'allowed_updates' => config('telegram.webhook_allowed_updates', []),
            ]),
        ];
    } catch (\RuntimeException $e) {
        $results['setWebhook'] = ['status' => 'error', 'error' => $e->getMessage()];
    }

    // Test getWebhookInfo
    try {
        $results['getWebhookInfo'] = [
            'status' => 'success',
            'response' => Telegram::getWebhookInfo(),
        ];
    } catch (\RuntimeException $e) {
        $results['getWebhookInfo'] = ['status' => 'error', 'error' => $e->getMessage()];
    }

    // Test deleteWebhook
    try {
        $results['deleteWebhook'] = [
            'status' => 'success',
            'response' => Telegram::deleteWebhook(),
        ];
    } catch (\RuntimeException $e) {
        $results['deleteWebhook'] = ['status' => 'error', 'error' => $e->getMessage()];
    }

    // Test getUpdates
    try {
        $results['getUpdates'] = [
            'status' => 'success',
            'response' => Telegram::getUpdates([
                'limit' => 10,
            ]),
        ];
    } catch (\RuntimeException $e) {
        $results['getUpdates'] = ['status' => 'error', 'error' => $e->getMessage()];
    }

    return $results;
});
```

```bash
curl http://test-project.test/validate-all-functionalities
```

This route tests all Telegram API methods and returns a JSON response with the status and results for each:

```json
{
  "sendMessage": {"status": "success", "response": {...}},
  "sendPhoto": {"status": "success", "response": {...}},
  "sendVideo": {"status": "success", "response": {...}},
  "sendDocument": {"status": "success", "response": {...}},
  "sendLocation": {"status": "success", "response": {...}},
  "sendContact": {"status": "success", "response": {...}},
  "setWebhook": {"status": "success", "response": {...}},
  "getWebhookInfo": {"status": "success", "response": {...}},
  "deleteWebhook": {"status": "success", "response": {...}},
  "getUpdates": {"status": "success", "response": {...}}
}
```

Run unit tests to verify the package:

```bash
composer test:unit
```

Check test coverage:

```bash
./vendor/bin/pest --coverage --min=100
```

## Contributing

Contributions are welcome! Please submit a pull request or open an issue on [GitHub](https://github.com/hugphp/telegram).

## License

This package is open-source software licensed under the [MIT License](LICENSE).

## Support

**Hugphp Telegram** was created by **[Micheal Ataklt](https://github.com/matakltm-code)**.

For questions or support, open an issue on [GitHub](https://github.com/hugphp/telegram) or contact the maintainer at [matakltm.code@gmail.com](mailto:matakltm.code@gmail.com).