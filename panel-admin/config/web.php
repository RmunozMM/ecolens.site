<?php

$params = require __DIR__ . '/params.php';
$db = require "../../recursos/db_resources.php";

Yii::setAlias('@recursos', dirname(__DIR__) . '/../recursos');

$config = [
    'id' => 'basic',
    'name' => $params["meta_author"],
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => $params['zona_horaria'],

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],

    'language' => $params['idioma_sitio'],
    'sourceLanguage' => $params['idioma_sitio'],
    'params' => $params,

    'modules' => [
        'api' => [
            'class' => 'app\modules\api\Module',
        ],
    ],

    'components' => [
        'request' => [
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
            'cookieValidationKey' => 'xxxxxxx',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'localhost',
                'username' => 'contacto@rogeliomunoz.cl',
                'password' => 'Thor2605_*',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'forceCopy' => true,
            'linkAssets' => false,
            'bundles' => [
                'yii\jui\DatePickerAsset' => [
                    'sourcePath' => '@vendor/yiisoft/yii2-jui-master/src',
                    'basePath' => '@webroot/assets',
                    'baseUrl' => '@web/assets',
                    'css' => [
                        'themes/smoothness/jquery-ui.css',
                    ],
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'categories' => ['yii\db\Command::query'],
                    'logFile' => '@app/runtime/logs/sql.log',
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => $params['zona_horaria'],
        ],
    ],
];

/**
 * 🔧 Habilita el módulo Debug y Gii solo si está en entorno de desarrollo.
 * También incluye tu IP pública (190.114.38.97) para acceso remoto.
 */
if (YII_ENV_DEV || (isset($params["debug"]) && $params["debug"] === "yes")) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '190.114.38.97'], // tu IP
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '190.114.38.97'],
    ];
}

return $config;