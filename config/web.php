<?php

require_once __DIR__ . '/env.php';
$params = array_merge(
    require __DIR__ . '/params.php',
);
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'My Validators',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'baseUrl' => env('BASE_URL', ''),
            'cookieValidationKey' => 'zTe0fBkg-V49bv-ZGQGbjsEkBd36duyT',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['site'], //index
        ],
        'authManager' => authConfig(),        
        /*'errorHandler' => [
            'errorAction' => 'site/error',
        ],*/
        'errorHandler' => [
            'class' => 'app\components\WebErrorHandler',
            'errorAction' => 'site/error',
        ],              
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => YII_ENV=='dev' ? true : false,
            'transport' => [
                'dsn' => env('MAILER_DSN', false),
            ],            
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'params' => $params,
];

if (!empty($params['reCaptcha.siteKey'])) {
    $config['components']['reCaptcha'] =  [
            'class' => \himiklab\yii2\recaptcha\ReCaptchaConfig::class,
            'siteKeyV2' => $params['reCaptcha.siteKey'],
            'secretV2'  => $params['reCaptcha.secretKey'],
        ];
}

if (YII_DEBUG) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
