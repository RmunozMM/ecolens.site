<?php
use yii\rest\UrlRule;

$apiRules = [];
$apiPath = __DIR__ . '/../modules/api/controllers';
$namespace = 'app\modules\api\controllers';

foreach (glob($apiPath . '/*Controller.php') as $file) {
    $className = basename($file, '.php'); // Ej: ArticuloController
    $controllerId = strtolower(preg_replace('/Controller$/', '', $className)); // Ej: articulo

    if ($controllerId === 'articulo') {
        // Para ArticuloController, definimos rutas extra para buscar por slug
        $apiRules[] = [
            'class' => UrlRule::class,
            'controller' => "api/$controllerId",
            'pluralize' => false,
            'extraPatterns' => [
                'GET slug/<slug>' => 'slug', // Nuevo endpoint: api/articulo/slug/mi-articulo
            ],
        ];
    } else {
        // Para otros controladores, solo las rutas estándar
        $apiRules[] = [
            'class' => UrlRule::class,
            'controller' => "api/$controllerId",
            'pluralize' => false,
        ];
    }
}

return $apiRules;
