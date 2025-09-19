<?php

return [
    'connections' => [
        'mysql' => [
            'url' => env('DATABASE_URL', 'mysql://user:password@localhost/database'),
            'options' => [
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => env('MYSQL_ATTR_SSL_VERIFY_SERVER_CERT', true),
            ],
        ]
    ],
    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => false, // disable to preserve original behavior for existing applications
    ],

];
