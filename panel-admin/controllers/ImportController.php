<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\BadRequestHttpException;
use yii\db\ActiveRecord;
use yii\db\ColumnSchema;
use app\models\forms\ImportForm;

class ImportController extends Controller
{
    public function actionIndex($modelClass, $fieldsMap = '[]')
    {
        // Validar que el modelo exista y extienda de ActiveRecord
        if (!class_exists($modelClass) || !is_subclass_of($modelClass, ActiveRecord::class)) {
            throw new BadRequestHttpException("El modelo especificado no es válido.");
        }
        
        $fieldsMap = json_decode($fieldsMap, true);
        $importForm = new ImportForm();
        $errors = [];
        $sqlStatements = [];

        if (Yii::$app->request->isPost) {
            $importForm->uploadFile = UploadedFile::getInstance($importForm, 'uploadFile');
            if (!$importForm->uploadFile) {
                $message = "⚠️ No se ha seleccionado ningún archivo.";
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['status' => 'error', 'message' => $message];
                }
                Yii::$app->session->setFlash('error', $message);
                return $this->redirect([$this->resolveIndex($modelClass)]);
            }
            if ($importForm->validate()) {
                $result = $this->processImport($modelClass, $fieldsMap, $importForm->uploadFile->tempName, $sqlStatements, $errors);
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    $result['errors'] = $errors;
                    return $result;
                }
                Yii::$app->session->setFlash($result['status'], $result['message']);
                return $this->render('index', [
                    'importForm'    => $importForm,
                    'modelClass'    => $modelClass,
                    'fieldsMap'     => $fieldsMap,
                    'sqlStatements' => $sqlStatements,
                    'errors'        => $errors,
                    'rowResults'    => $result['rowResults'] ?? []
                ]);
            } else {
                $message = "Error de validación.";
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['status' => 'error', 'message' => $message, 'errors' => $importForm->errors];
                }
                Yii::$app->session->setFlash('error', $message);
            }
        }
        return $this->render('index', [
            'importForm'    => $importForm,
            'modelClass'    => $modelClass,
            'fieldsMap'     => $fieldsMap,
            'sqlStatements' => [],
            'errors'        => [],
            'rowResults'    => []
        ]);
    }

    /**
     * Procesa la importación de registros desde el CSV y genera un log detallado por fila.
     * Además, detecta de forma genérica los campos relacionados a creación y modificación,
     * asignándoles la fecha/hora actual si no se proporcionan en el CSV.
     */
    private function processImport($modelClass, $fieldsMap, $filePath, &$sqlStatements, &$errors)
    {
        if (filesize($filePath) === 0) {
            return [
                'status' => 'error',
                'message' => 'El archivo CSV está vacío.',
                'rowResults' => []
            ];
        }
        
        /** @var ActiveRecord $modelInstance */
        $modelInstance = new $modelClass();
        $primaryKey = $modelInstance::primaryKey()[0] ?? null;
        
        // 1) Construir autoMap de columnas reales (nombres en minúsculas)
        $tableSchema = $modelInstance->getTableSchema();
        $allDbColumns = array_keys($tableSchema->columns);
        $autoMap = [];
        foreach ($allDbColumns as $col) {
            $autoMap[mb_strtolower($col)] = $col;
        }
        
        // 2) Normalizar el fieldsMap (alias) para que se pueda usar tanto el alias como el nombre real
        $fieldsMapNormalized = [];
        foreach ($fieldsMap as $csvColumn => $attribute) {
            $fieldsMapNormalized[mb_strtolower($csvColumn)] = $attribute;
            if (mb_strtolower($csvColumn) !== mb_strtolower($attribute)) {
                $fieldsMapNormalized[mb_strtolower($attribute)] = $attribute;
            }
        }
        
        // 3) Combinar autoMap y fieldsMapNormalized (prioridad a lo definido en fieldsMap)
        $combinedMap = array_merge($autoMap, $fieldsMapNormalized);
        if ($primaryKey) {
            $combinedMap[mb_strtolower($primaryKey)] = $primaryKey;
            if (!isset($combinedMap['id'])) {
                $combinedMap['id'] = $primaryKey;
            }
        }
        
        // 4) Abrir y leer el CSV
        $handle = fopen($filePath, 'r');
        $headers = [];
        $imported = 0;
        $updated = 0;
        $rowIndex = 0;
        $rowResults = [];
        $requiredAttributes = $this->getRequiredAttributes($modelInstance);
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                $rowIndex++;
                if (empty($headers)) {
                    // Se asume la primera fila como encabezados; quitar posibles BOM y espacios
                    $headers = array_map(fn($h) => mb_strtolower(trim($h, "\xEF\xBB\xBF ")), $data);
                    $rowIndex--; // No contar la fila de encabezados
                    continue;
                }
                
                $recordData = [];
                $recordId = null;
                foreach ($headers as $i => $normalizedHeader) {
                    if (isset($combinedMap[$normalizedHeader])) {
                        $attribute = $combinedMap[$normalizedHeader];
                        $value = isset($data[$i]) ? trim($data[$i]) : '';
                        $recordData[$attribute] = $value;
                    }
                }
                // Forzar asignación de recordId: si existe el atributo con el nombre de la PK en recordData, usarlo.
                if ($primaryKey && isset($recordData[$primaryKey]) && $recordData[$primaryKey] !== '') {
                    $recordId = $recordData[$primaryKey];
                } else {
                    $recordId = null;
                }
                // Si la columna es de tipo entero, forzar conversión
                if ($primaryKey && $recordId !== null && isset($tableSchema->columns[$primaryKey]) && $tableSchema->columns[$primaryKey]->type === 'integer') {
                    $recordId = (int)$recordId;
                }
                
                // 5) Normalizar los valores según el tipo de cada columna
                foreach ($recordData as $key => $value) {
                    if (isset($tableSchema->columns[$key])) {
                        $columnSchema = $tableSchema->columns[$key];
                        $recordData[$key] = $this->normalizeValue($value, $columnSchema);
                    }
                }
                
                // 5.1) Autocompletar campos de fecha de creación y modificación si no vienen en el CSV
                foreach ($tableSchema->columns as $colName => $colSchema) {
                    // Si el nombre de la columna contiene "creacion" o "creation" y no se proporcionó valor
                    if ((stripos($colName, 'creacion') !== false || stripos($colName, 'creation') !== false)
                        && (!isset($recordData[$colName]) || $recordData[$colName] === null || $recordData[$colName] === '')
                    ) {
                        $recordData[$colName] = date('Y-m-d H:i:s');
                    }
                    // Si el nombre de la columna contiene "modificacion" o "modification" y no se proporcionó valor
                    if ((stripos($colName, 'modificacion') !== false || stripos($colName, 'modification') !== false)
                        && (!isset($recordData[$colName]) || $recordData[$colName] === null || $recordData[$colName] === '')
                    ) {
                        $recordData[$colName] = date('Y-m-d H:i:s');
                    }
                }
                
                // 6) Validar campos obligatorios
                $missingFields = [];
                foreach ($requiredAttributes as $required) {
                    if (!isset($recordData[$required]) || $recordData[$required] === '' || $recordData[$required] === null) {
                        $missingFields[] = $required;
                    }
                }
                if (!empty($missingFields)) {
                    $errorMsg = "Campos obligatorios vacíos: " . implode(', ', $missingFields);
                    $errors[] = ['row' => $rowIndex, 'message' => $errorMsg];
                    $rowResults[] = ['row' => $rowIndex, 'status' => 'error', 'message' => $errorMsg, 'sql' => ''];
                    continue;
                }
                
                // 7) Crear o actualizar el registro
                if ($recordId !== null) {
                    $domainModel = $modelClass::find()->where([$primaryKey => $recordId])->one();
                    if (!$domainModel) {
                        $domainModel = new $modelClass();
                    }
                } else {
                    $domainModel = new $modelClass();
                    unset($recordData[$primaryKey]);
                }
                // Asignar escenario "import" si está definido; de lo contrario, se usa el scenario por defecto.
                $scenarios = $domainModel->scenarios();
                if (array_key_exists('import', $scenarios)) {
                    $domainModel->scenario = 'import';
                }
                $domainModel->attributes = $recordData;
                if ($recordId !== null) {
                    $domainModel->{$primaryKey} = $recordId;
                }
                
                // 8) Generar SQL de depuración
                if ($recordId !== null) {
                    $updateParts = [];
                    foreach ($recordData as $k => $v) {
                        $updateParts[] = $v === null ? "$k = NULL" : "$k = '" . addslashes((string)$v) . "'";
                    }
                    $rowSql = "UPDATE {$domainModel->tableName()} SET " . implode(', ', $updateParts) . " WHERE {$primaryKey} = '" . addslashes($recordId) . "';";
                    $sqlStatements[] = $rowSql;
                } else {
                    $columns = implode(', ', array_keys($recordData));
                    $values  = implode(", ", array_map(function($val) {
                        return $val === null ? "NULL" : "'" . addslashes((string)$val) . "'";
                    }, array_values($recordData)));
                    $rowSql = "INSERT INTO {$domainModel->tableName()} ($columns) VALUES ($values);";
                    $sqlStatements[] = $rowSql;
                }
                
                // 9) Guardar el registro y registrar el resultado por fila
                if ($domainModel->save()) {
                    if ($recordId !== null) {
                        $updated++;
                        $rowResults[] = [
                            'row' => $rowIndex,
                            'status' => 'updated',
                            'message' => "Registro con ID $recordId actualizado",
                            'sql' => $rowSql
                        ];
                    } else {
                        $imported++;
                        $newId = $domainModel->getPrimaryKey();
                        $rowResults[] = [
                            'row' => $rowIndex,
                            'status' => 'inserted',
                            'message' => "Nuevo registro insertado con ID $newId",
                            'sql' => $rowSql
                        ];
                    }
                } else {
                    $errorMsg = json_encode($domainModel->getErrors());
                    $errors[] = ['row' => $rowIndex, 'message' => $errorMsg];
                    $rowResults[] = [
                        'row' => $rowIndex,
                        'status' => 'error',
                        'message' => $errorMsg,
                        'sql' => $rowSql
                    ];
                }
            }
            fclose($handle);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'status' => 'error',
                'message' => "Error en la importación: " . $e->getMessage(),
                'rowResults' => []
            ];
        }
        
        if (!empty($errors)) {
            return [
                'status' => 'error',
                'message' => "Algunas filas no se importaron.",
                'rowResults' => $rowResults
            ];
        }
        
        return [
            'status' => 'success',
            'message' => "Se importaron $imported registros nuevos y se actualizaron $updated registros existentes.",
            'rowResults' => $rowResults
        ];
    }
    
    /**
     * Obtiene los atributos requeridos definidos en las reglas del modelo.
     */
    private function getRequiredAttributes(ActiveRecord $modelInstance)
    {
        $requiredAttributes = [];
        foreach ($modelInstance->rules() as $rule) {
            if (isset($rule[1]) && $rule[1] === 'required') {
                if (is_array($rule[0])) {
                    $requiredAttributes = array_merge($requiredAttributes, $rule[0]);
                } else {
                    $requiredAttributes[] = $rule[0];
                }
            }
        }
        return array_unique($requiredAttributes);
    }
    
    /**
     * Normaliza el valor del CSV según el tipo de columna en la BD.
     * Retorna NULL si el valor es vacío y la columna lo permite.
     */
    private function normalizeValue(string $value, ColumnSchema $columnSchema)
    {
        if ($value === '') {
            return $columnSchema->allowNull ? null : $this->defaultFor($columnSchema);
        }
        switch ($columnSchema->type) {
            case 'integer':
                return (int)$value;
            case 'double':
            case 'float':
            case 'decimal':
                return (float)str_replace(',', '.', $value);
            case 'boolean':
                $trueValues = ['1', 'true', 'yes', 'si'];
                return in_array(strtolower($value), $trueValues);
            case 'date':
            case 'datetime':
            case 'timestamp':
                $parsed = strtotime($value);
                if ($parsed === false) {
                    return $columnSchema->allowNull ? null : $this->defaultFor($columnSchema);
                }
                return date('Y-m-d H:i:s', $parsed);
            default:
                return $value;
        }
    }
    
    /**
     * Valor por defecto para columnas que no permiten NULL y reciben cadena vacía o valor inválido.
     */
    private function defaultFor(ColumnSchema $columnSchema)
    {
        switch ($columnSchema->type) {
            case 'integer':
            case 'double':
            case 'float':
            case 'decimal':
                return 0;
            case 'boolean':
                return false;
            case 'date':
            case 'datetime':
            case 'timestamp':
                return date('Y-m-d H:i:s');
            default:
                return '';
        }
    }
    
    /**
     * Devuelve la URL del index del modelo.
     */
    protected function resolveIndex($modelClass)
    {
        $shortName = (new \ReflectionClass($modelClass))->getShortName();
        return strtolower($shortName) . '/index';
    }
}