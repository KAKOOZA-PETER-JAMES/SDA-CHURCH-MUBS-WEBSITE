<?php

namespace App\Support;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmation as RegistrationConfirmationMail;
use App\Mail\SystemRegistrationAlert as SystemRegistrationAlertMail;
use App\Mail\AdminUpdateNotification as AdminUpdateNotificationMail;

class BrevoMailer
{
    private const MAILERS = ['brevo_smtp', 'brevo_smtp_ssl'];

    public function sendRegistrationConfirmation(array $data): bool
    {
        $recipient = trim((string) ($data['email'] ?? ''));

        return $this->sendThroughBrevoSmtp(
            $recipient,
            new RegistrationConfirmationMail($data),
            'Registration confirmation email failed (SMTP).'
        );
    }

    public function sendSystemRegistrationAlert(array $data): bool
    {
        $recipient = trim((string) config('mail.system_notification_address', ''));

        if ($recipient === '') {
            $recipient = trim((string) config('mail.from.address', ''));
        }

        return $this->sendThroughBrevoSmtp(
            $recipient,
            new SystemRegistrationAlertMail($data),
            'System registration alert email failed (SMTP).'
        );
    }

    public function sendUpdateNotification(string $recipient, array $content): bool
    {
        $recipient = trim($recipient);

        return $this->sendThroughBrevoSmtp(
            $recipient,
            new AdminUpdateNotificationMail($content),
            'Update notification email failed (SMTP).'
        );
    }

    private function sendThroughBrevoSmtp(string $recipient, Mailable $mailable, string $errorMessage): bool
    {
        if ($recipient === '') {
            return false;
        }

        $attemptErrors = [];

        foreach (self::MAILERS as $mailer) {
            try {
                Mail::mailer($mailer)->to($recipient)->send($mailable);

                return true;
            } catch (\Throwable $e) {
                $attemptErrors[$mailer] = $e->getMessage();
            }
        }

        try {
            Log::error($errorMessage, [
                'recipient' => $recipient,
                'mailers_tried' => self::MAILERS,
                'errors' => $attemptErrors,
            ]);
        } catch (\Throwable $e) {
            // Keep the original flow resilient even if logging fails.
        }

        return false;
    }
}