<?php

return [

    'connections' => [
        'mysql' => [
            'dump' => [
                'dump_binary_path' => env('MYSQL_DUMP_BINARY_PATH', '/usr/bin'),
            ],
        ],
    ],

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => false, // disable to preserve original behavior for existing applications
    ],

];
