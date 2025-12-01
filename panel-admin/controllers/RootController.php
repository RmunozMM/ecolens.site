<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\User;
use yii\db\Exception;

class RootController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only'  => ['index','view','create','update','delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $usuario = Yii::$app->user->identity->usu_id ?? null;
                            return User::checkRoleByUserId($usuario, [1,2,3]);
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionTablas()
    {
        return $this->render('tablas');
    }
    
    /**
     * Vista de consultas SQL, que:
     * - Solo admite SELECT
     * - Simula "tabla no encontrada" si detecta la palabra "usuarios"
     * - Oculta el nombre real de la base de datos en errores
     * - Permite exportar CSV en formato pipe "|"
     */
    public function actionConsultasql()
    {
        $model = new \yii\base\DynamicModel(['consulta']);
        $model->addRule(['consulta'], 'string');

        if ($model->load(Yii::$app->request->post())) {
            $consulta  = $model->consulta;
            $exportCsv = Yii::$app->request->post('btn-exportar-csv') !== null;

            // 1) Solo SELECT
            if (!preg_match('/^\s*select\b/i', $consulta)) {
                // Mensaje genérico
                Yii::$app->session->setFlash('error', 'Error en la consulta SQL.');
                return $this->render('consultasql', ['model' => $model]);
            }

            // 2) Forzar que si aparece "usuarios", disparemos un error tipo "table not found" 
            if (preg_match('/\busuarios\b/i', $consulta)) {
                // Simular que la tabla "usuarios" no existe. 
                // Llamamos un método "tableNotFoundError" que lanza una excepción 
                // igual que si la tabla no existiera.
                return $this->tableNotFoundError($model);
            }

            // 3) Ejecutar y capturar errores
            try {
                $resultado = Yii::$app->db->createCommand($consulta)->queryAll();

                if (empty($resultado)) {
                    // Consulta sin resultados
                    Yii::$app->session->setFlash('error', 'La consulta no arrojó resultados.');
                } else {
                    // Si es exportación
                    if ($exportCsv) {
                        return $this->exportarCsv($resultado);
                    }
                    // Caso normal: mostrar resultados
                    return $this->render('consultasql', [
                        'model'     => $model,
                        'resultado' => $resultado,
                    ]);
                }
            } catch (Exception $e) {
                // Verificamos si es un error "tabla o vista no encontrada"
                // 42S02 -> Table or view not found
                // 1146  -> "Table '...' doesn't exist"
                $errorInfo = $e->errorInfo ?? null; // a veces es null
                $sqlState  = $errorInfo[0] ?? null; // ej: '42S02'
                $errorCode = $errorInfo[1] ?? null; // ej: 1146

                if ($sqlState === '42S02' || $errorCode == 1146) {
                    // Error de tabla no encontrada
                    Yii::$app->session->setFlash('error', 'La tabla o vista no existe.');
                } else {
                    // Otro error: mensaje genérico
                    Yii::$app->session->setFlash('error', 'Error en la consulta SQL.');
                }
            }
        }

        // Render sin resultados
        return $this->render('consultasql', ['model' => $model]);
    }

    /**
     * Forzar un error "tabla no encontrada" si detectamos la palabra "usuarios".
     */
    private function tableNotFoundError($model)
    {
        // Simulamos la misma excepción que lanza MySQL cuando no existe la tabla:
        // SQLSTATE[42S02], código 1146
        // Sin exponer el nombre real de la BD. 
        $exception = new Exception(
            "SQLSTATE[42S02]: Base table or view not found: 1146 Table 'xxxxx' doesn't exist",
            1146
        );
        // Modo "manual" de llenar errorInfo
        $exception->errorInfo = ['42S02', 1146, 'Base table or view not found: 1146 Table'];

        // Lo capturamos en el mismo try-catch, así que en vez de throw new,
        // podemos manejarlo como si estuviéramos en "catch" 
        // Retornamos la vista con el mismo error.
        Yii::$app->session->setFlash('error', 'La tabla o vista no existe.');
        return $this->render('consultasql', ['model' => $model]);
    }

    /**
     * Exporta CSV con delimitador "|"
     */
    private function exportarCsv(array $resultado)
    {
        $fp = fopen('php://temp', 'w');

        $columnas = array_keys($resultado[0]);
        fputcsv($fp, $columnas, '|');

        foreach ($resultado as $fila) {
            fputcsv($fp, $fila, '|');
        }

        rewind($fp);
        $csv = stream_get_contents($fp);
        fclose($fp);

        $response = Yii::$app->response;
        $response->format = Response::FORMAT_RAW;
        $response->setDownloadHeaders('export.csv', 'text/csv', false, strlen($csv));
        $response->content = $csv;

        return $response;
    }

    /**
     * Retorna JSON con tablas y columnas,
     * sin incluir "usuarios" para autocompletado.
     */
    public function actionGetTables()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
    
        try {
            $connection = Yii::$app->db;
            $tables     = $connection->schema->getTableNames();
            $data       = [];
    
            foreach ($tables as $table) {
                // Evitar sugerir "usuarios"
                if (strtolower($table) === 'usuarios') {
                    continue;
                }
                $columns = $connection->schema->getTableSchema($table)->columnNames;
                $data[$table] = $columns;
            }
    
            return $data;
        } catch (\Exception $e) {
            return ['error' => 'No se pudieron obtener las tablas: ' . $e->getMessage()];
        }
    }

    // SiteController.php (o el controlador que uses)
public function actionStatus()
{
    $user = Yii::$app->user->identity;
    if (!$user || $user->usu_rol_id != 1) {
        throw new \yii\web\ForbiddenHttpException('No tienes permisos para ver esta sección.');
    }

    // Fecha de instalación
    $installPath = dirname(Yii::getAlias('@app')) . '/install/control/installed.lock';
    $installedAt = file_exists($installPath)
        ? trim(file_get_contents($installPath))
        : null;

    // Parámetros visibles desde config/params.php
    $visibleParams = [
        'adminEmail',
        'supportEmail',
        'baseUrl',
        'cacheDuration',
        'enableSchemaCache',
    ];
    $appParams = [];
    foreach ($visibleParams as $key) {
        if (isset(Yii::$app->params[$key])) {
            $appParams[$key] = Yii::$app->params[$key];
        }
    }

    // Métricas y entorno adicional
    $language           = Yii::$app->language;
    $timeZone           = Yii::$app->timeZone;
    $gitVersion         = trim(@exec('git rev-parse --short HEAD'));
    $currentMemoryUsage = round(memory_get_usage(true) / 1024 / 1024, 2); // en MB
    $loadedExtensions   = count(get_loaded_extensions());
    $load               = @sys_getloadavg(); // [1min, 5min, 15min]
    $loadAvg            = $load ? "{$load[0]}, {$load[1]}, {$load[2]}" : 'n/a';

    return $this->render('status', [
        'user'               => $user,
        'cmsVersion'         => Yii::$app->params['cms_version'] ?? '5.0.0',
        'mysqlVersion'       => Yii::$app->db->createCommand("SELECT VERSION()")->queryScalar(),
        'diskSpaceFree'      => round(disk_free_space("/") / 1024 / 1024 / 1024, 2),
        'rutaUploads'        => Yii::getAlias('@recursos/uploads'),
        'installedAt'        => $installedAt,
        'appParams'          => $appParams,
        'language'           => $language,
        'timeZone'           => $timeZone,
        'gitVersion'         => $gitVersion,
        'currentMemoryUsage' => $currentMemoryUsage,
        'loadedExtensions'   => $loadedExtensions,
        'loadAvg'            => $loadAvg,
    ]);
}

}