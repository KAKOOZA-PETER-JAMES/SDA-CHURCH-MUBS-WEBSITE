<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'brevo' => [
        'key' => env('BREVO_API_KEY'),
        'sender_email' => env('BREVO_SENDER_EMAIL', env('MAIL_FROM_ADDRESS')),
        'sender_name' => env('BREVO_SENDER_NAME', env('MAIL_FROM_NAME')),
    ],

    'smtp' => [
        'host' => env('BREVO_SMTP_HOST', 'smtp-relay.brevo.com'),
        'port' => env('BREVO_SMTP_PORT', 587),
        'encryption' => env('BREVO_SMTP_ENCRYPTION', 'tls'),
        'username' => env('BREVO_SMTP_USERNAME'),
        'password' => env('BREVO_SMTP_PASSWORD'),
    ],

    'smtp_ssl' => [
        'port' => env('BREVO_SMTP_SSL_PORT', 465),
        'encryption' => env('BREVO_SMTP_SSL_ENCRYPTION', 'ssl'),
    ],

    'mail_system_notification_address' => env('MAIL_SYSTEM_NOTIFICATION_ADDRESS', 'admin@yourdomain.com'),
];
