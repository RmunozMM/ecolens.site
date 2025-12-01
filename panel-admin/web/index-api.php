<?php 

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

// 🔥 ELIMINA O COMENTA ESTA LÍNEA - ES REDUNDANTE Y ESTÁ MAL UBICADA
// \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

$config = require __DIR__ . '/../config/web-api.php'; // Carga la config que ya incluye el formato JSON

(new yii\web\Application($config))->run();