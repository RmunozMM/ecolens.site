<?php
namespace app\helpers;   // <— ESTE NAMESPACE ES CLAVE

use yii\helpers\Url;

class SitioUtilidades
{
    /**
     * Corrige rutas relativas tipo ../recursos/ → ../../recursos/
     */
    public static function fixContentUrls($html)
    {
        if (!is_string($html) || trim($html) === '') {
            return '';
        }
        return str_replace('../recursos/', '../../recursos/', $html);
    }

    /**
     * Reemplaza {{link:slug}} por enlaces dinámicos de Yii2
     */
    public static function procesarLinksDinamicos($html)
    {
        if (!is_string($html) || trim($html) === '') {
            return $html;
        }

        try {
            return preg_replace_callback('/\{\{link:([a-z0-9_-]+)\}\}/i', function ($matches) {
                $slug = trim($matches[1]);
                return Url::to(['site/pagina', 'slug' => $slug]);
            }, $html);
        } catch (\Throwable $e) {
            return $html;
        }
    }
}