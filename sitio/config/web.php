<?php
use yii\base\Security;

$params = require __DIR__ . '/params.php';

return [
    'id'         => 'app-sitio',
    'basePath'   => dirname(__DIR__),
    'bootstrap'  => ['log'],
    

    'components' => [
        // Request: validación de cookies + desactivar CSRF en frontend público
        'request' => [
            'cookieValidationKey'  => (new Security())->generateRandomString(),
            'enableCsrfValidation' => false,
        ],

        // Cache (opcional, pero recomendado para ContenidoService)
        /*
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        */

        // Cache: DummyCache en DEV, FileCache en producción
        'cache' => [
            'class' => YII_ENV_DEV
                ? \yii\caching\DummyCache::class
                : \yii\caching\FileCache::class,
        ],


        // Log: errores y warnings
        'log' => [
            'targets' => [
                [
                    'class'  => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        // URL manager: si usas rutas "bonitas"
        'urlManager' => [
            'enablePrettyUrl'     => true,
            'showScriptName'      => false,
            'enableStrictParsing' => false,
            'rules'               => [
                ''                       => 'site/index',
                '<slug>'                 => 'site/pagina',
                'taxonomias' => 'site/taxonomias',                     // listado
                'taxonomias/<slug:[a-z0-9\-]+>' => 'site/taxonomia',   // detalle
                'taxonomias/<slugTax:[\w\-]+>/<slugEspecie:[\w\-]+>' => 'site/especie',
                 'detalle-deteccion/<id:\d+>' => 'site/detalle-deteccion',
                 

            ],
        ],
    ],

    // Módulo API
    'modules' => [
        'api' => [
            'class' => \app\modules\api\Module::class,
        ],
    ],

    'params' => $params,
];