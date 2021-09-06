<?php

return [
    'disks' => [
        'app' => [
            'driver' => 'local',
            'root' => storage_path().'/app'
        ],
        'ftp' => [
            'driver' => 'ftp',
            'host' => env('FTP_HOST'),
            'username' => env('FTP_USERNAME'),
            'password' => env('FTP_PASSWORD'),
        ],
    ]
];
