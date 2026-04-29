<?php

return [
    'snm' => [
        // File settings
        'file_prefix' => 'json_data_',
        'tmp_dir' => sys_get_temp_dir(), // Uses system temporary directory

        // Node Manager SSH config
        // 'host' => env('NM_SSH_HOST', 'send.example.com'),
        // 'username' => env('NM_SSH_USERNAME', 'user'),
        // 'password' => env('NM_SSH_PASSWORD', 'password'),
        'remote_path' => env('NM_SSH_REMOTE_PATH', '/path/to/send'),

        // Retry and timeout settings
        'max_retries' => env('NM_MAX_RETRIES', 3),
        'base_sleep' => env('NM_BASE_SLEEP', 1), // seconds
        'wait_timeout' => env('NM_WAIT_TIMEOUT', 60), // seconds
        'wait_interval' => env('NM_WAIT_INTERVAL', 2), // seconds
    ],
];