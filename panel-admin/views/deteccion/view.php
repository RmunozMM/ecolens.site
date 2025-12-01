<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\Deteccion $model */

$this->title = "Detección #{$model->det_id}";
$this->params['breadcrumbs'][] = ['label' => 'Detecciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div class="deteccion-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="fa fa-clipboard-check"></i> Revisar', ['revisar', 'det_id' => $model->det_id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-trash"></i> Eliminar', ['delete', 'det_id' => $model->det_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de que deseas eliminar esta detección? Esta acción no se puede deshacer.',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('<i class="fa fa-arrow-left"></i> Volver', ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'det_id',
            [
                'attribute' => 'det_imagen',
                'label' => 'Imagen procesada',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'det_imagen',
                    ]);
                },
            ],
            'det_origen_archivo',
            [
                'attribute' => 'det_confianza_router',
                'format' => ['decimal', 4],
                'label' => 'Confianza (Router)',
            ],
            [
                'attribute' => 'det_confianza_experto',
                'format' => ['decimal', 4],
                'label' => 'Confianza (Experto)',
            ],
            'det_tiempo_router_ms',
            'det_tiempo_experto_ms',
            [
                'attribute' => 'taxonomia.tax_nombre',
                'label' => 'Taxonomía',
            ],
            [
                'attribute' => 'especie.esp_nombre_cientifico',
                'label' => 'Especie',
            ],
            'det_latitud',
            'det_longitud',
            'det_ubicacion_textual',
            [
                'attribute' => 'det_dispositivo_tipo',
                'value' => $model->displayDispositivo(),
            ],
            [
                'attribute' => 'det_sistema_operativo',
                'value' => $model->displaySO(),
            ],
            [
                'attribute' => 'det_navegador',
                'value' => $model->displayNavegador(),
            ],
            'det_ip_cliente',
            [
                'attribute' => 'det_fuente',
                'value' => $model->displayFuente(),
            ],
            [
                'attribute' => 'det_estado',
                'value' => $model->displayEstado(),
            ],
            [
                'attribute' => 'det_revision_estado',
                'value' => $model->displayRevision(),
            ],
            [
                'label' => 'Observador',
                'value' => function ($model) {
                    if (!$model->observador) return '(no asignado)';
                    $o = $model->observador;
                    return "{$o->obs_nombre} ({$o->obs_usuario})";
                },
            ],
            [
                'attribute' => 'validador.usu_nombre',
                'label' => 'Validado por',
            ],
            'det_validacion_fecha:datetime',
            'det_fecha:datetime',
        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>

</div>