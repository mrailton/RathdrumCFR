<?php

declare(strict_types=1);

use Illuminate\Support\Str;

return [

    'stores' => [
        'healthcheck' => [
            'driver' => 'redis',
            'connection' => 'cache',
            'lock_connection' => 'default',
        ],
    ],

];
