<?php

namespace App\Mail\Transport;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Email;

class BrevoApiTransport extends AbstractTransport
{
    public function __construct(
        protected string $apiKey,
        protected string $fromName = 'BAYATY'
    ) {
        parent::__construct();
    }

protected function doSend(SentMessage $message): void
{
    /** @var Email $email */
    $email = $message->getOriginalMessage();

    $to = array_map(
        fn ($addr) => ['email' => $addr->getAddress(), 'name' => $addr->getName() ?: null],
        $email->getTo()
    );

    $from = $email->getFrom()[0] ?? null;

    $payload = [
        'sender' => [
            'name' => $from?->getName() ?: $this->fromName,
            'email' => $from?->getAddress(),
        ],
        'to' => $to,
        'subject' => $email->getSubject(),
        'htmlContent' => $email->getHtmlBody() ?: $email->getTextBody(),
    ];

    \Log::info('Brevo payload', $payload);

    $response = Http::withHeaders([
        'api-key' => $this->apiKey,
        'Content-Type' => 'application/json',
    ])->post('https://api.brevo.com/v3/smtp/email', $payload);

    \Log::info('Brevo response', [
        'status' => $response->status(),
        'body' => $response->body(),
    ]);

    if ($response->failed()) {
        throw new \RuntimeException('Brevo API mail failed: '.$response->body());
    }
}

public function __toString(): string
    {
        return 'brevo+api';
    }
}
