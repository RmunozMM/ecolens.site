<?php
/**
 * config/ecolens_env.php
 * Configuración compartida para PHP y JS — entorno local y producción.
 */
declare(strict_types=1);

// --------------------------------------------------------
// ✅ Definición segura de constantes (sin duplicarlas)
// --------------------------------------------------------
if (!defined('ECO_LOCAL_HOSTS')) {
    define('ECO_LOCAL_HOSTS', ['localhost', '127.0.0.1']);
}
if (!defined('ECO_LOCAL_PORT')) {
    define('ECO_LOCAL_PORT', '8888');
}
if (!defined('ECO_LOCAL_PREFIX_PANEL')) {
    define('ECO_LOCAL_PREFIX_PANEL', '/ecolens.site/panel-admin/web');
}
if (!defined('ECO_LOCAL_PREFIX_SITE')) {
    define('ECO_LOCAL_PREFIX_SITE', '/ecolens.site/sitio/web');
}
if (!defined('ECO_LOCAL_PREDICT_URL')) {
    define('ECO_LOCAL_PREDICT_URL', 'http://127.0.0.1:8001/predict');
}

// Producción (cuando subamos a VPS)
if (!defined('ECO_PROD_HOST')) {
    define('ECO_PROD_HOST', 'ecolens.site');
}
if (!defined('ECO_PROD_PREFIX_PANEL')) {
    define('ECO_PROD_PREFIX_PANEL', '/panel-admin/web');
}
if (!defined('ECO_PROD_PREFIX_SITE')) {
    define('ECO_PROD_PREFIX_SITE', '/sitio/web');
}
if (!defined('ECO_PROD_PREDICT_URL')) {
    define('ECO_PROD_PREDICT_URL', 'http://64.176.10.15:9000/predict');
}

// --------------------------------------------------------
// 🔹 Detección de entorno
// --------------------------------------------------------
$hostHeader = $_SERVER['HTTP_HOST'] ?? php_uname('n');
[$hostname] = explode(':', $hostHeader);
$isLocal    = in_array($hostname, ECO_LOCAL_HOSTS, true);

if ($isLocal) {
    $API_BASE  = "http://{$hostname}:" . ECO_LOCAL_PORT . ECO_LOCAL_PREFIX_PANEL;
    $SITE_BASE = "http://{$hostname}:" . ECO_LOCAL_PORT . ECO_LOCAL_PREFIX_SITE;
    $PREDICT   = ECO_LOCAL_PREDICT_URL;
} else {
    $API_BASE  = "https://" . ECO_PROD_HOST . ECO_PROD_PREFIX_PANEL;
    $SITE_BASE = "https://" . ECO_PROD_HOST . ECO_PROD_PREFIX_SITE;
    $PREDICT   = ECO_PROD_PREDICT_URL;
}

// --------------------------------------------------------
// 🔹 Entorno unificado
// --------------------------------------------------------
$env = [
    'isLocal'   => $isLocal,
    'API_BASE'  => rtrim($API_BASE, '/'),
    'SITE_BASE' => rtrim($SITE_BASE, '/'),
    'endpoints' => [
        'predict'         => $PREDICT,
        'login'           => $API_BASE . '/api/observador/login',
        'logout'          => $API_BASE . '/api/observador/logout',   // 👈 nuevo
        'whoami'          => $API_BASE . '/api/observador/whoami',
        'registrar'       => $API_BASE . '/api/deteccion/registrar',
        'mis_detecciones' => $SITE_BASE . '/mis-detecciones',
        
    ],
];

// --------------------------------------------------------
// ✅ Nunca imprimir ni hacer header, solo devolver array
// --------------------------------------------------------
return $env;