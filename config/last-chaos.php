<?php

return [
    'database' => [
        'connection' => [
            'host' => env('LAST_CHAOS_CONNECTION_HOST', 'localhost'),
            'port' => env('LAST_CHAOS_CONNECTION_PORT', 3306),
            'username' => env('LAST_CHAOS_CONNECTION_USERNAME', 'root'),
            'password' => env('LAST_CHAOS_CONNECTION_PASSWORD', ''),
        ],

        'schemas' => [
            'data' => env('LAST_CHAOS_DATABASE_SCHEMA_DATA', 'data'),
            'db' => env('LAST_CHAOS_DATABASE_SCHEMA_DB', 'db'),
            'auth' => env('LAST_CHAOS_DATABASE_SCHEMA_AUTH', 'auth'),
            'post' => env('LAST_CHAOS_DATABASE_SCHEMA_POST', 'post'),
        ]
    ],

    'auth' => [
        'hash' => env('LAST_CHAOS_AUTH_HASH', 'sha256'),
        'salt' => env('LAST_CHAOS_AUTH_SALT', '')
    ],

    'max_accounts_per_user' => env('LAST_CHAOS_MAX_ACCOUNTS_PER_USER', 1)
];
