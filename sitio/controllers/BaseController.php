<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\services\ContenidoService; // <- se usa el servicio, pero el controller sigue en app\controllers

class BaseController extends Controller
{
    public function beforeAction($action)
    {
        $contenido = ContenidoService::getAll();

        // Normaliza opciones a objeto
        if (isset($contenido->opciones) && is_array($contenido->opciones)) {
            $contenido->opciones = (object)$contenido->opciones;
        } elseif (!isset($contenido->opciones) || !is_object($contenido->opciones)) {
            $contenido->opciones = new \stdClass();
        }

        Yii::$app->view->params['contenido'] = $contenido;
        Yii::$app->view->params['opciones']  = $contenido->opciones;

        return parent::beforeAction($action);
    }
}
