<?php
return [

    'config_perm' => [
        'type' => 2,
        'description' => 'System config',
    ],
    'client' => [
        'type' => 1,
        'description' => 'Client',
        'children' => [
        ],
    ],
    
    'admin' => [
        'type' => 1,
        'description' => 'Admin',
        'children' => [
            'client',
        ],
    ],    

    'root' => [
        'type' => 1,
        'description' => 'Root',
        'children' => [
            'config_perm',
            'admin',
            'client',
        ],
    ],
];
