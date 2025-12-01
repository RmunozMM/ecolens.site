<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Users;

class ActividadRecienteWidget extends Widget
{
    public function run()
    {
        $configPath = Yii::getAlias('@app/config/actividad_tablas.php');
        $tablasConfig = [];
        $iconos = [];
        $usuarios = [];

        if (file_exists($configPath)) {
            $tablas = require $configPath;
            foreach ($tablas as $t) {
                $nombreTabla = $t['nombre'];
                $tablasConfig[$nombreTabla] = $t;
                $iconos[$nombreTabla] = $t['icono'] ?? 'fa-pen|#999';
            }
        }

        $registros = Yii::$app->db->createCommand("
            SELECT * FROM actividad_reciente ORDER BY updated_at DESC LIMIT 5
        ")->queryAll();

        ob_start();
        ?>
        <div class="dashboard-card mt-4">
            <h5><i class="fa fa-rotate me-1"></i> Actividad Reciente</h5>

            <?php if (empty($registros)): ?>
                <p class="text-muted mb-0">No hay registros aún.</p>
            <?php else: ?>
                <ul class="list-group list-group-flush mt-3">
                    <?php foreach ($registros as $r): ?>
                        <?php
                            $tabla = $r['tabla'];
                            $conf = $tablasConfig[$tabla] ?? null;
                            if (!$conf || empty($r['id'])) continue;

                            $campoId = $conf['campo_id'];
                            $valorId = $r['id'];
                            $controller = rtrim($tabla, 's');
                            $url = Url::to(["/$controller/view", $campoId => $valorId]);

                            $userId = $r['updated_by'] ?? null;
                            if ($userId && !isset($usuarios[$userId])) {
                                $user = Users::findOne($userId);
                                $usuarios[$userId] = $user ? $user->usu_username : 'Usuario desconocido';
                            }
                            $nombreUsuario = $usuarios[$userId] ?? 'Sistema';

                            list($iconClass, $iconColor) = explode('|', $iconos[$tabla] ?? 'fa-pen|#999');
                            $fechaRelativa = Yii::$app->formatter->asRelativeTime($r['updated_at']);

                            $titulo = Html::encode($r['nombre_registro']);
                            $nombreUsuario = Html::encode($nombreUsuario);
                            $tablaLabel = ucfirst(rtrim($tabla, 's'));
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="d-flex align-items-start gap-2">
                                <i class="fa <?= $iconClass ?>" style="color: <?= $iconColor ?>; margin-top: 2px;"></i>
                                <div>
                                    <?= $nombreUsuario ?> editó <?= $tablaLabel ?> <a href="<?= $url ?>"><strong>"<?= $titulo ?>"</strong></a>
                                </div>
                            </div>
                            <div class="text-muted small"><?= $fechaRelativa ?></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
