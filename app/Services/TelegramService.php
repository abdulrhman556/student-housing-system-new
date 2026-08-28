<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    /**
     * Compatibility wrapper for existing booking/payment callers.
     */
    public function send(array|string $payload)
    {
        if (is_string($payload)) {
            return $this->sendMessage($payload);
        }

        return Http::post(
            "https://api.telegram.org/bot" . config('services.telegram.token') . "/sendMessage",
            [
                'chat_id' => $payload['chat_id'] ?? config('services.telegram.chat_id'),
                'text' => $payload['message'] ?? $payload['text'] ?? '',
                'parse_mode' => 'HTML',
            ]
        );
    }

    public function sendMessage(string $message)
    {
        return Http::post(
            "https://api.telegram.org/bot" . config('services.telegram.token') . "/sendMessage",
            [
                'chat_id' => config('services.telegram.chat_id'),
                'text' => $message,
                'parse_mode' => 'HTML',
            ]
        );
    }
}
