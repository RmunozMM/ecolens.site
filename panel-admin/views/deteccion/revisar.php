<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Deteccion;

/** @var yii\web\View $this */
/** @var app\models\Deteccion $model */

$this->title = "Revisar detección #{$model->det_id}";
$this->params['breadcrumbs'][] = ['label' => 'Detecciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="deteccion-revisar">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group btn_save">
        <?= Html::submitButton(
            '<i class="fa fa-check"></i> Guardar revisión',
            ['class' => 'btn btn-success']
        ) ?>
        <?= Html::a(
            '<i class="fa fa-arrow-left"></i> Volver',
            ['index'],
            ['class' => 'btn btn-secondary']
        ) ?>
        <?= Html::a(
            '<i class="fa fa-external-link-alt"></i> Ver detalle público',
            "https://ecolens.site/sitio/web/detalle-deteccion/{$model->det_id}",
            [
                'class' => 'btn btn-outline-primary',
                'target' => '_blank',
                'data-pjax' => '0',
            ]
        ) ?>
    </div>

    <div class="row mt-3">
        <!-- Columna izquierda -->
        <div class="col-md-5 text-center">
            <h4>Imagen procesada</h4>
            <div class="mb-3 border rounded p-2 bg-light">
                <?= \app\widgets\ManageImages\FrontWidget::widget([
                    'model' => $model,
                    'atributo' => 'det_imagen',
                    'htmlOptions' => [
                        'style'   => 'max-width:100%; border-radius:8px;',
                        'loading' => 'lazy',
                    ],
                ]) ?>
            </div>

            <?php if ($model->especie && $model->especie->esp_imagen): ?>
                <h4>
                    Imagen de referencia
                    (<?= Html::encode($model->especie->esp_nombre_cientifico) ?>)
                </h4>
                <div class="border rounded p-2 bg-light">
                    <?= \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model->especie,
                        'atributo' => 'esp_imagen',
                        'htmlOptions' => [
                            'style'   => 'max-width:100%; border-radius:8px;',
                            'loading' => 'lazy',
                        ],
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Columna derecha -->
        <div class="col-md-7">
            <div class="card p-3 shadow-sm">

                <?= $form->field($model, 'det_estado')->dropDownList(
                    Deteccion::optsDetEstado(),
                    ['prompt' => 'Seleccionar estado...']
                ) ?>

                <?= $form->field($model, 'det_revision_estado')->dropDownList(
                    Deteccion::optsDetRevision(),
                    ['prompt' => 'Seleccionar estado de revisión...']
                ) ?>

                <?= $form->field($model, 'det_observaciones')->textarea([
                    'rows'  => 8,
                    'id'    => 'tinyMCE',
                    'class' => 'tinymce',
                ]) ?>

                <hr>
                <h5><i class="fa fa-robot text-success"></i> Datos de inferencia</h5>
                <div class="ml-2">
                    <strong>Confianza Router:</strong>
                    <?= $model->det_confianza_router !== null
                        ? number_format($model->det_confianza_router, 4)
                        : '—' ?><br>
                    <strong>Confianza Experto:</strong>
                    <?= $model->det_confianza_experto !== null
                        ? number_format($model->det_confianza_experto, 4)
                        : '—' ?><br>
                    <strong>Tiempo Router:</strong>
                    <?= Html::encode($model->det_tiempo_router_ms) ?> ms<br>
                    <strong>Tiempo Experto:</strong>
                    <?= Html::encode($model->det_tiempo_experto_ms) ?> ms
                </div>

                <hr>
                <h5><i class="fa fa-leaf text-primary"></i> Clasificación</h5>
                <div class="ml-2">
                    <strong>Taxonomía:</strong>
                    <?= $model->taxonomia
                        ? Html::a(
                            Html::encode($model->taxonomia->tax_nombre),
                            ['taxonomia/view', 'tax_id' => $model->taxonomia->tax_id],
                            ['target' => '_blank']
                        )
                        : '(no asignada)' ?><br>

                    <strong>Especie:</strong>
                    <?= $model->especie
                        ? Html::a(
                            Html::encode($model->especie->esp_nombre_cientifico),
                            ['especie/view', 'esp_id' => $model->especie->esp_id],
                            ['target' => '_blank']
                        )
                        : '(no asignada)' ?>
                </div>

                <hr>
                <h5><i class="fa fa-map-marker-alt text-danger"></i> Ubicación</h5>
                <div class="ml-2">
                    <strong>Latitud:</strong>
                    <?= Html::encode($model->det_latitud) ?><br>
                    <strong>Longitud:</strong>
                    <?= Html::encode($model->det_longitud) ?><br>
                    <strong>Descripción:</strong>
                    <?= Html::encode($model->det_ubicacion_textual) ?>
                </div>

                <hr>
                <h5><i class="fa fa-desktop text-secondary"></i> Datos del dispositivo</h5>
                <div class="ml-2">
                    <strong>Dispositivo:</strong>
                    <?= Html::encode($model->displayDispositivo()) ?><br>
                    <strong>Sistema operativo:</strong>
                    <?= Html::encode($model->displaySO()) ?><br>
                    <strong>Navegador:</strong>
                    <?= Html::encode($model->displayNavegador()) ?><br>
                    <strong>IP cliente:</strong>
                    <?= Html::encode($model->det_ip_cliente) ?><br>
                    <strong>Fuente:</strong>
                    <?= Html::encode($model->displayFuente()) ?>
                </div>

                <hr>
                <h5><i class="fa fa-user text-info"></i> Observador</h5>
                <div class="ml-2">
                    <?= $model->observador
                        ? Html::encode($model->observador->obs_nombre . " ({$model->observador->obs_usuario})")
                        : '(anónimo)' ?>
                </div>

                <hr>
                <div class="ml-2">
                    <strong>Fecha detección:</strong>
                    <?= $model->det_fecha
                        ? Yii::$app->formatter->asDatetime($model->det_fecha)
                        : '—' ?><br>
                    <strong>Última modificación:</strong>
                    <?= $model->updated_at
                        ? Yii::$app->formatter->asDatetime($model->updated_at)
                        : '—' ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
