<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host=" . env('DB_HOST', '') . ";dbname=" . env('DB_DATABASE', ''),
    'username' => env('DB_USERNAME', ''),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
