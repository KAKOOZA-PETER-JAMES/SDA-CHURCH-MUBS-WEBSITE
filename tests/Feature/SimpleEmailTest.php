<?php

namespace Tests\Feature;

use App\Mail\AdminUpdateNotification;
use App\Mail\RegistrationConfirmation;
use App\Mail\SystemRegistrationAlert;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Simple tests to verify email classes can be instantiated 
 * and Mail facade works with fakes.
 */
class SimpleEmailTest extends TestCase
{
    public function test_registration_confirmation_mail_class_exists(): void
    {
        $data = [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0700000000',
        ];

        $mail = new RegistrationConfirmation($data);
        $this->assertNotNull($mail);
        $this->assertEquals($data, $mail->data);
    }

    public function test_system_registration_alert_mail_class_exists(): void
    {
        $data = [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0700000000',
        ];

        $mail = new SystemRegistrationAlert($data);
        $this->assertNotNull($mail);
        $this->assertEquals($data, $mail->data);
    }

    public function test_admin_update_notification_mail_class_exists(): void
    {
        $content = [
            'updates' => [
                [
                    'title' => 'Test Update',
                    'details' => 'This is a test.',
                ],
            ],
        ];

        $mail = new AdminUpdateNotification($content);
        $this->assertNotNull($mail);
        $this->assertEquals($content, $mail->content);
    }

    public function test_mail_facade_fake_works(): void
    {
        Mail::fake();

        // Verify Mail::fake() doesn't throw exception
        $this->assertTrue(true);

        Mail::assertNothingSent();
    }

    public function test_mail_can_be_sent_with_fake(): void
    {
        Mail::fake();

        $data = [
            'full_name' => 'Test Member',
            'email' => 'member@example.com',
        ];

        Mail::to('test@example.com')->send(new RegistrationConfirmation($data));

        Mail::assertSent(RegistrationConfirmation::class);
    }
}
