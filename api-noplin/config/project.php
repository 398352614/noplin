<?php
return [
    'root' => [
        'username' => env('ROOT_USERNAME') ?? env('MAIL_USERNAME'),
        'password' => env('ROOT_PASSWORD') ?? '123456',
    ],
    'number' => [
        'prefix' => env('NUMBER_PREFIX') ?? config('app.name'),
        'length' => env('NUMBER_LENGTH') ?? 6,
    ],
    'backup' => [
        'mysql' => env('BACKUP_MYSQL')
    ],
    'telescope' => [
        'path' => env('TELESCOPE_PATH')
    ]
];