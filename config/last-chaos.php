<?php

return [
    'database' => [
        'connection' => [
            'host' => env('LAST_CHAOS_CONNECTION_HOST', 'lckb.dev'),
            'port' => env('LAST_CHAOS_CONNECTION_PORT', 3306),
            'username' => env('LAST_CHAOS_CONNECTION_USERNAME', 'lcdaTa22'),
            'password' => env('LAST_CHAOS_CONNECTION_PASSWORD', 'nA18429V'),
        ],

        'schemas' => [
            'data' => env('LAST_CHAOS_DATABASE_SCHEMA_DATA', 'lc_2022_data'),
            'db' => env('LAST_CHAOS_DATABASE_SCHEMA_DB', 'lc_2022_db'),
            'auth' => env('LAST_CHAOS_DATABASE_SCHEMA_AUTH', 'lc_2022_auth'),
            'post' => env('LAST_CHAOS_DATABASE_SCHEMA_POST', 'lc_2022_post'),
        ]
    ],

    'auth' => [
        'hash' => env('LAST_CHAOS_AUTH_HASH', 'sha256'),
        'salt' => env('LAST_CHAOS_AUTH_SALT', '')
    ],

    'max_accounts_per_user' => env('LAST_CHAOS_MAX_ACCOUNTS_PER_USER', '')
];
