<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\db\ActiveRecord;

class ExportController extends Controller
{
    public function actionIndex($modelClass)
    {
        // Validar que el modelo exista
        if (!class_exists($modelClass)) {
            throw new \yii\web\NotFoundHttpException("El modelo no existe.");
        }

        // Obtener los datos del modelo
        $query = $modelClass::find();
        $data = $query->asArray()->all();

        // Definir el nombre del archivo
        $filename = strtolower((new \ReflectionClass($modelClass))->getShortName()) . "_export_" . date('Ymd') . ".csv";

        // Configurar la respuesta para descarga de CSV
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        Yii::$app->response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        // Abrir buffer de salida
        $fp = fopen('php://output', 'w');

        // Agregar BOM para compatibilidad con Excel (UTF-8)
        fwrite($fp, "\xEF\xBB\xBF");

        // Si hay datos, obtener encabezados de la primera fila; de lo contrario, obtener atributos del modelo
        if (!empty($data)) {
            $headers = array_keys($data[0]);
        } else {
            /** @var ActiveRecord $modelInstance */
            $modelInstance = new $modelClass();
            $headers = $modelInstance->attributes();
            Yii::$app->session->setFlash('warning', 'No hay registros para exportar. Se generó un archivo con la estructura esperada.');
        }

        // Escribir encabezados en el CSV con delimitador ";" y valores entre comillas
        fputcsv($fp, $headers, ';', '"');

        // Escribir cada fila en el CSV
        if (!empty($data)) {
            foreach ($data as $row) {
                // Convertir valores NULL a cadena vacía para evitar problemas
                $row = array_map(function ($value) {
                    return is_null($value) ? '' : (string) $value;
                }, $row);
                fputcsv($fp, $row, ';', '"');
            }
        }

        fclose($fp);
        Yii::$app->end();
    }
}