<?php

namespace Tests\Feature;

use App\Mail\AdminUpdateNotification;
use App\Mail\RegistrationConfirmation;
use App\Mail\SystemRegistrationAlert;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailNotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_finalize_sends_member_and_system_emails(): void
    {
        Mail::fake();

        config([
            'mail.system_notification_address' => 'mubssdachurch@gmail.com',
            'mail.from.address' => 'mubssdachurch@gmail.com',
        ]);

        $data = [
            'full_name' => 'Test Member',
            'email' => 'peterkakooza968@gmail.com',
            'phone' => '0700000000',
            'gender' => 'Male',
            'address' => 'Kampala',
            'category' => 'Other',
            'year_of_study' => null,
            'program_name' => null,
            'program_category' => null,
            'year_of_entry' => null,
            'hostel_name' => null,
            'renting_area' => null,
            'division_of_study' => 'Bible Study',
            'family' => 'Jericho',
            'wants_updates' => 1,
        ];

        $response = $this->withSession([
            'registration_data' => $data,
        ])->post(route('registration.finalize'));

        $response->assertRedirect(route('home'));

        Mail::assertSent(RegistrationConfirmation::class, function (RegistrationConfirmation $mail) use ($data): bool {
            return $mail->hasTo($data['email'])
                && ($mail->data['full_name'] ?? null) === $data['full_name'];
        });

        Mail::assertSent(SystemRegistrationAlert::class, function (SystemRegistrationAlert $mail) use ($data): bool {
            return $mail->hasTo('mubssdachurch@gmail.com')
                && ($mail->data['email'] ?? null) === $data['email'];
        });
    }

    public function test_admin_update_emails_only_go_to_subscribed_members(): void
    {
        Mail::fake();

        $subscribed = Registration::create([
            'full_name' => 'Subscribed Member',
            'email' => 'subscribed@example.com',
            'phone' => '0711111111',
            'gender' => 'Female',
            'address' => 'Kampala',
            'category' => 'Other',
            'year_of_study' => null,
            'program_name' => null,
            'program_category' => null,
            'year_of_entry' => null,
            'hostel_name' => null,
            'renting_area' => null,
            'division_of_study' => 'Bible Study',
            'family' => 'Jordan',
            'wants_updates' => 1,
        ]);

        $unsubscribed = Registration::create([
            'full_name' => 'Unsubscribed Member',
            'email' => 'unsubscribed@example.com',
            'phone' => '0722222222',
            'gender' => 'Male',
            'address' => 'Kampala',
            'category' => 'Other',
            'year_of_study' => null,
            'program_name' => null,
            'program_category' => null,
            'year_of_entry' => null,
            'hostel_name' => null,
            'renting_area' => null,
            'division_of_study' => 'Bible Study',
            'family' => 'Hebron',
            'wants_updates' => 0,
        ]);

        $response = $this->withSession([
            'is_admin_dashboard' => true,
        ])->post(route('admin.dashboard.save'), [
            'updates' => [
                [
                    'month' => 'April',
                    'title' => 'Test Update',
                    'date_range' => 'April 15 - April 20',
                    'department' => 'Admin',
                    'details' => 'This is a test update email.',
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        Mail::assertSent(AdminUpdateNotification::class, function (AdminUpdateNotification $mail) use ($subscribed): bool {
            return $mail->hasTo($subscribed->email);
        });

        Mail::assertNotSent(AdminUpdateNotification::class, function (AdminUpdateNotification $mail) use ($unsubscribed): bool {
            return $mail->hasTo($unsubscribed->email);
        });
    }
}