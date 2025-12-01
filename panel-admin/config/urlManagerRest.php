<?php

use yii\rest\UrlRule;

// Ruta al módulo de controladores API
$apiPath = __DIR__ . '/../modules/api/controllers';

// Namespace base para los controladores API
$namespace = 'app\modules\api\controllers';

$apiRules = [];

foreach (glob($apiPath . '/*Controller.php') as $file) {
    $className = basename($file, '.php'); // Ej: ArticuloController
    $controllerId = strtolower(preg_replace('/Controller$/', '', $className)); // Ej: articulo

    $apiRules[] = [
        'class' => UrlRule::class,
        'controller' => "$namespace\\$controllerId",
        'pluralize' => false,
    ];
}

return [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => $apiRules,
];
