<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;

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

        \App\Jobs\SendTelegramNotification::dispatch($message);
    }

    /**
     * إشعار الأدمن بتسجيل owner جديد محتاج موافقة
     */
    public function newOwnerRegistration(User $user): void
    {
        $message = "
🆕 <b>تسجيل مالك سكن جديد</b>

👤 <b>الاسم:</b> {$user->fname} {$user->lname}

📧 <b>الإيميل:</b> {$user->email}

📱 <b>الهاتف:</b> {$user->phone}

🆔 <b>الرقم القومي:</b> " . ($user->national_id ?? 'غير مسجل') . "

📌 <b>الحالة:</b> في انتظار الموافقة

🆔 <b>رقم الحساب:</b> #{$user->id}
";

        \App\Jobs\SendTelegramNotification::dispatch($message);
    }
}
