<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppCustomAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl  = '@web'; // Esto resuelve correctamente /SITIOS/CMS_BASE_V3/panel-admin/web/

    public $css = [
        'css/custom-theme.css',
        'css/high-contrast.css',
        'https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css',
        'https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css',
    ];

    public $js = [
        'js/sidebar/sidebar-toggle.js',
        'js/accesibilidad/accesibilidad.js',
        'js/fontawesome/fontawesome.js',
        'js/helpers/font-size.js',
        'js/reloj.js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js',
        'https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}