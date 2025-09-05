<?php

declare(strict_types=1);

use function Hyperf\Support\env;

return [
    'default' => env('MAIL_MAILER', 'smtp'),
    'from' => [
        'name' => env('MAIL_FROM_NAME', 'Example'),
        'address' => env('MAIL_FROM_ADDRESS', 'no-reply@example.com'),
    ],
    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'options' => [
                'host' => env('MAIL_HOST', 'smtp.exemplo.com'),
                'port' => env('MAIL_PORT', 587),
                'encryption' => env('MAIL_ENCRYPTION', 'tls'),
                'username' => env('MAIL_USERNAME'),
                'password' => env('MAIL_PASSWORD'),
                'auth' => env('MAIL_AUTH_ENABLED', false),
            ],
        ],
    ],
];
