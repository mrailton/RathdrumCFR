<?php

declare(strict_types=1);


return [

    'stores' => [
        'healthcheck' => [
            'driver' => 'redis',
            'connection' => 'cache',
            'lock_connection' => 'default',
        ],
    ],

];
