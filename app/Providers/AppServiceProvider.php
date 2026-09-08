<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Send users to the frontend verification screen, which calls the
        // existing public verification endpoint and shows the result clearly.
        VerifyEmail::toMailUsing(function ($notifiable) {
            $frontendUrl = rtrim(config('app.frontend_url'), '/');
            $signedBackendUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
                ],
                absolute: false
            );
            parse_str(parse_url($signedBackendUrl, PHP_URL_QUERY) ?? '', $query);
            // Route parameters are part of the signed URL path, so pass them
            // explicitly to the frontend confirmation page as query values.
            $query['id'] = $notifiable->getKey();
            $query['hash'] = sha1($notifiable->getEmailForVerification());
            $verificationUrl = $frontendUrl.'/verify-email.html?'.http_build_query($query);

            return (new MailMessage)
                ->subject('تأكيد البريد الإلكتروني - BAYATY')
                ->line('اضغط على الزر التالي لتأكيد بريدك الإلكتروني وإكمال إنشاء الحساب.')
                ->action('تأكيد البريد الإلكتروني', $verificationUrl)
                ->line('إذا لم تقم بإنشاء هذا الحساب، يمكنك تجاهل هذه الرسالة.');
        });

        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            $frontendUrl = rtrim(config('app.frontend_url'), '/');
            $resetUrl = $frontendUrl.'/reset-password.html?'.http_build_query([
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);

            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('إعادة تعيين كلمة السر - BAYATY')
                ->line('وصلنا طلب لإعادة تعيين كلمة السر الخاصة بحسابك.')
                ->action('إعادة تعيين كلمة السر', $resetUrl)
                ->line('هذا الرابط صالح لمدة 60 دقيقة فقط.')
                ->line('إذا لم تطلب إعادة تعيين كلمة السر، يمكنك تجاهل هذه الرسالة.');
        });
    }
}
