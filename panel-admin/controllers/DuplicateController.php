<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\db\ActiveRecord;
use yii\base\Model;

class DuplicateController extends Controller
{
    public function actionIndex($modelClass, $id)
    {
        // Verificamos que la clase de modelo exista
        if (!class_exists($modelClass)) {
            throw new NotFoundHttpException("El modelo no existe.");
        }

        // Obtenemos el registro original
        $original = $modelClass::findOne($id);
        if (!$original) {
            throw new NotFoundHttpException("Registro no encontrado.");
        }

        // Obtenemos los atributos del original
        $attrs = $original->attributes;

        // Eliminamos las claves primarias
        foreach ($modelClass::primaryKey() as $pk) {
            unset($attrs[$pk]);
        }

        // Detectar y modificar los campos con restricción UNIQUE
        $uniqueAttributes = $this->getUniqueAttributes(new $modelClass());

        foreach ($uniqueAttributes as $uniqueField) {
            if (!empty($attrs[$uniqueField])) {
                $attrs[$uniqueField] = $this->generateUniqueValue($modelClass, $uniqueField, $attrs[$uniqueField]);
            }
        }

        // Crear nuevo registro duplicado
        $newRecord = new $modelClass();
        $newRecord->attributes = $attrs;
        $newRecord->isNewRecord = true;

        // Intentamos guardar
        if (!$newRecord->save()) {
            Yii::$app->session->setFlash('error', 
                'No se pudo duplicar el registro: ' . print_r($newRecord->errors, true)
            );
        } else {
            Yii::$app->session->setFlash('success', 'Registro duplicado correctamente.');
        }

        // Redirigir a la página anterior
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Obtiene los atributos únicos del modelo dinámicamente desde las reglas de validación.
     */
    private function getUniqueAttributes(Model $model)
    {
        $uniqueFields = [];
        foreach ($model->rules() as $rule) {
            if (isset($rule[1]) && $rule[1] === 'unique') {
                $uniqueFields = array_merge($uniqueFields, (array) $rule[0]);
            }
        }
        return array_unique($uniqueFields);
    }

    /**
     * Genera un valor único basado en un campo único del modelo, agregando un sufijo correlativo.
     */
    private function generateUniqueValue($modelClass, $field, $originalValue)
    {
        $count = 1;
        $newValue = $originalValue;

        while ($modelClass::find()->where([$field => $newValue])->exists()) {
            $newValue = $originalValue . " (" . $count . ")";
            $count++;
        }

        return $newValue;
    }
}