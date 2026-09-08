<?php

namespace App\Jobs;

use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SendTelegramNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;

    public function __construct(protected array|string $payload)
    {
    }

    public function handle(TelegramService $telegramService): void
    {
        $response = $telegramService->send($this->payload);

        if (is_object($response) && method_exists($response, 'successful') && ! $response->successful()) {
            throw new \RuntimeException('Telegram API request failed: ' . $response->body());
        }
    }

    public function failed(Throwable $exception): void
    {
        report($exception);
    }
}
