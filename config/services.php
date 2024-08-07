<?php

declare(strict_types=1);

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'google' => [
        'maps' => [
            'api_key' => env('GOOGLE_MAPS_API_KEY'),
        ],
    ],

];
