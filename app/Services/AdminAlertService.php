<?php

namespace App\Services;

use App\Models\Booking;

class AdminAlertService
{
    public function __construct(
        protected TelegramService $telegram
    ) {}

    /**
     * إشعار الأدمن بوجود حجز جديد
     */
    public function newBooking(Booking $booking): void
    {
        $booking->loadMissing([
            'student',
            'unit.property',
        ]);

        $message = "
🏠 <b>طلب حجز جديد</b>

👤 <b>الطالب:</b> {$booking->student->fname} {$booking->student->lname}

🏢 <b>العقار:</b> {$booking->unit->property->title}

🚪 <b>الوحدة:</b> {$booking->unit->title}

📅 <b>تاريخ الدخول:</b> {$booking->check_in_date}

📌 <b>الحالة:</b> {$booking->status}

🆔 <b>رقم الحجز:</b> #{$booking->id}
";

        $response = $this->telegram->sendMessage($message);

        if (! $response->successful()) {
            report('Telegram Error: '.$response->body());
        }
    }
}
