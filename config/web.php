<?php

$params = require __DIR__ . '/params.php';
$db     = require __DIR__ . '/db.php';
$routes = require __DIR__ . '/routes.php';

$config = [
    'id'         => 'basic',
    'basePath'   => dirname(__DIR__),
    'bootstrap'  => ['log'],
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request'      => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'wD-vaSGKI1B2QeLgCoTwcLXVNhXiq_FH',
        ],
        'cache'        => [
            'class' => 'yii\caching\FileCache',
        ],
        'user'         => [
            'identityClass'   => 'app\models\User',
            'enableAutoLogin' => TRUE,
        ],
        'panel'        => [
            'class'           => 'yii\web\User',
            'identityClass'   => 'app\models\data\SysAdmin',
            'enableAutoLogin' => FALSE,
            'idParam'         => '__admin',
            'loginUrl'        => '/panel/login'
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer'       => [
            'class'            => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => TRUE,
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db_master'    => $db['master'],
        'urlManager'   => [
            'enablePrettyUrl' => TRUE,
            'showScriptName'  => FALSE,
            'rules'           => $routes,
        ],
    ],
    'modules'    => [
        'panel' => [
            'class' => 'app\modules\panel\BaseModule',
        ],
    ],
    'params'     => $params,
];

if (!YII_ENV_PROD) {
    $config['bootstrap'][]      = 'debug';
    $config['modules']['debug'] = [
        'class'      => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];

    $config['bootstrap'][]    = 'gii';
    $config['modules']['gii'] = [
        'class'      => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];
}

return $config;
