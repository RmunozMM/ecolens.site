<?php
use yii\helpers\Html;
use yii\helpers\Json;

$this->title = 'Estado Técnico del Sistema';
// Agrego el breadcrumb de “Inicio” para que aparezca en el encabezado
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-status">

    <!-- Encabezado común: breadcrumbs + título -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h1 class="h3 mb-0"><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="widget p-3 rounded bg-light border">
        <ul class="list-group list-group-flush fs-6">
            <li class="list-group-item"><strong>Versión PHP:</strong> <?= phpversion() ?></li>
            <li class="list-group-item"><strong>Versión Yii:</strong> <?= Yii::getVersion() ?></li>
            <li class="list-group-item"><strong>Versión CMS:</strong> <?= Html::encode($cmsVersion) ?></li>
            <?php if ($installedAt): ?>
                <li class="list-group-item">
                    <strong>Fecha de instalación:</strong> <?= Html::encode($installedAt) ?>
                </li>
            <?php endif; ?>
            <li class="list-group-item"><strong>Entorno:</strong> <?= YII_ENV_DEV ? 'Desarrollo' : 'Producción' ?></li>
            <li class="list-group-item"><strong>Ruta base:</strong> <?= Html::encode(Yii::getAlias('@app')) ?></li>
            <li class="list-group-item"><strong>Ruta uploads:</strong> <?= Html::encode($rutaUploads) ?></li>
            <li class="list-group-item"><strong>Usuario conectado:</strong> <?= Html::encode($user->usu_username) ?></li>
            <li class="list-group-item"><strong>DSN BD:</strong> <?= Html::encode(Yii::$app->db->dsn) ?></li>
            <li class="list-group-item"><strong>Versión MySQL:</strong> <?= Html::encode($mysqlVersion) ?></li>
            <li class="list-group-item"><strong>Uso memoria actual:</strong> <?= $currentMemoryUsage ?> MB</li>
            <li class="list-group-item"><strong># Extensiones PHP:</strong> <?= $loadedExtensions ?></li>
            <li class="list-group-item"><strong>Load Average:</strong> <?= Html::encode($loadAvg) ?></li>
            <li class="list-group-item"><strong>Espacio libre:</strong> <?= $diskSpaceFree ?> GB</li>
            <li class="list-group-item"><strong>Idioma:</strong> <?= Html::encode($language) ?></li>
            <li class="list-group-item"><strong>Zona horaria:</strong> <?= Html::encode($timeZone) ?></li>
        </ul>

        <?php if (!empty($appParams)): ?>
            <div class="mt-4">
                <a class="btn btn-sm btn-outline-secondary"
                   data-bs-toggle="collapse"
                   href="#appParamsCollapse"
                   role="button"
                   aria-expanded="false">
                    Parámetros de Aplicación
                </a>
                <div class="collapse mt-2" id="appParamsCollapse">
                    <ul class="list-group list-group-flush fs-6">
                        <?php foreach ($appParams as $key => $value): ?>
                            <li class="list-group-item">
                                <strong><?= Html::encode($key) ?>:</strong>
                                <code>
                                    <?= Html::encode(
                                        is_scalar($value)
                                            ? $value
                                            : Json::encode($value, JSON_UNESCAPED_UNICODE)
                                    ) ?>
                                </code>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
