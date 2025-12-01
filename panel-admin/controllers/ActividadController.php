<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class ActividadController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow'   => true,
                        'roles'   => ['@'],
                        'actions' => ['rebuild-view', 'configurar', 'guardar-configuracion'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Reconstruye la vista SQL `actividad_reciente` usando las tablas definidas.
     */
    public function actionRebuildView()
    {
        $configPath = Yii::getAlias('@app/config/actividad_tablas.php');

        if (!file_exists($configPath)) {
            Yii::$app->session->setFlash('error', 'No se encontró el archivo de configuración.');
            return $this->redirect(['site/index']);
        }

        $tablas = require($configPath);
        $this->reconstruirVistaActividad($tablas);

        return $this->redirect(['site/index']);
    }

    /**
     * Vista para configurar las tablas incluidas en la auditoría.
     */
    public function actionConfigurar()
    {
        if (!Yii::$app->user->identity || Yii::$app->user->identity->usu_rol_id != 1) {
            throw new \yii\web\ForbiddenHttpException('No tienes permisos para acceder a esta sección.');
        }

        $configPath = Yii::getAlias('@app/config/actividad_tablas.php');
        $tablas = file_exists($configPath) ? require($configPath) : [];

        return $this->render('configurar', [
            'tablas' => $tablas,
        ]);
    }

    /**
     * Guarda el archivo PHP con el nuevo arreglo y reconstruye la vista.
     */
    public function actionGuardarConfiguracion()
    {
        if (!Yii::$app->user->identity || Yii::$app->user->identity->usu_rol_id != 1) {
            throw new \yii\web\ForbiddenHttpException('No tienes permisos para realizar esta acción.');
        }

        $post = Yii::$app->request->post('Tablas', []);

        $nuevasTablas = [];
        foreach ($post as $tabla) {
            if (!empty($tabla['nombre']) && !empty($tabla['campo_id']) && !empty($tabla['campo_nombre'])) {
                $nuevasTablas[] = [
                    'nombre'       => $tabla['nombre'],
                    'campo_id'     => $tabla['campo_id'],
                    'campo_nombre' => $tabla['campo_nombre'],
                    'icono'        => $tabla['icono'] ?? '',
                ];
            }
        }

        $ruta = Yii::getAlias('@app/config/actividad_tablas.php');
        $contenido = "<?php\nreturn " . var_export($nuevasTablas, true) . ";\n";

        try {
            file_put_contents($ruta, $contenido);
            Yii::$app->session->setFlash('success', 'Configuración guardada correctamente.');
            $this->reconstruirVistaActividad($nuevasTablas);
        } catch (\Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            Yii::$app->session->setFlash('error', 'No se pudo guardar la configuración: ' . $e->getMessage());
        }

        return $this->redirect(['configurar']);
    }

    /**
     * Construye la vista `actividad_reciente` en base a las tablas definidas.
     */
    private function reconstruirVistaActividad(array $tablas): bool
    {
        $unionSql = '';
        foreach ($tablas as $i => $tabla) {
            $sql = "SELECT 
                        '{$tabla['nombre']}' AS tabla, 
                        {$tabla['campo_id']} AS id, 
                        updated_by, 
                        updated_at, 
                        {$tabla['campo_nombre']} AS nombre_registro
                    FROM {$tabla['nombre']}";

            $unionSql .= ($i === 0) ? $sql : " UNION ALL {$sql}";
        }

        $vista = "
            CREATE OR REPLACE VIEW actividad_reciente AS 
            SELECT * FROM ({$unionSql}) AS unioned 
            ORDER BY updated_at DESC 
            LIMIT 100
        ";

        try {
            Yii::$app->db->createCommand($vista)->execute();
            Yii::$app->session->addFlash('success', 'Vista `actividad_reciente` reconstruida.');
            return true;
        } catch (\Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            Yii::$app->session->addFlash('error', 'Error al crear la vista: ' . $e->getMessage());
            return false;
        }
    }
}
