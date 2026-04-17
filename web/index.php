<?php

// Add these three lines
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

require_once __DIR__ . '/../config/env.php';
defined('YII_ENV') or define('YII_ENV', env('YII_ENV', 'prod'));
defined('YII_DEBUG') or define('YII_DEBUG', envBool('YII_DEBUG', YII_ENV=='dev'));

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
