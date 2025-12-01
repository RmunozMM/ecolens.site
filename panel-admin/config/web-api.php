<?php
use yii\web\Response;
use yii\web\JsonResponseFormatter;

$params = require __DIR__ . '/params.php';
$db     = require "../../recursos/db_resources.php";
Yii::setAlias('@recursos', dirname(__DIR__) . '/../recursos');

$config = [
    'id'            => 'api',
    'basePath'      => dirname(__DIR__),
    'bootstrap'     => ['log'],
    'language'      => $params['idioma_sitio'],
    'sourceLanguage'=> $params['idioma_sitio'],
    'name'          => $params['meta_author'],
    'params'        => $params,

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],

    'modules' => [
        'api' => [
            'class' => 'app\modules\api\Module',
        ],
    ],

    'components' => [
        'request' => [
            'enableCookieValidation' => true,
            'enableCsrfValidation'   => true,
            'cookieValidationKey'    => 'api_cookie_validation_key',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\DummyCache',
        ],
        'user' => [
            'identityClass'   => 'app\models\User',
            'enableAutoLogin' => false,
            'enableSession'   => false,
        ],
        'errorHandler' => [
            'class' => yii\web\ErrorHandler::class,
        ],
        'db' => $db,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                    'logFile'=> '@app/runtime/logs/api.log',
                ],
            ],
        ],

        // ────────────────────────────────────────────────────────────────────
        // Configuración de response
        // ────────────────────────────────────────────────────────────────────
        'response' => [
            'class' => Response::class,
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
                $response->headers->set('Pragma', 'no-cache');
                $response->headers->set('Expires', '0');
            },
            'formatters' => [
                Response::FORMAT_JSON => [
                    'class'         => JsonResponseFormatter::class,
                    'prettyPrint'   => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules' => [
                // … tus reglas REST (Articulo, Contenido, Contacto, etc.) …
            ],
        ],

        'formatter' => [
            'class'           => yii\i18n\Formatter::class,
            'defaultTimeZone' => $params['zona_horaria'],
        ],
    ],
];

return $config;