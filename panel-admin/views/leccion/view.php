<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\LibreriaHelper;
use app\helpers\AuditoriaGridColumns;



/** @var yii\web\View $this */
/** @var app\models\Leccion $model */

$this->title = $model->lec_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Lecciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="leccion-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'lec_id' => $model->lec_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'lec_id' => $model->lec_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de querer eliminar esta lección? Esta acción no se puede deshacer.',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'lec_id',
            [
                'label' => 'Título',
                'attribute' => 'lec_titulo',
            ],
            [
                'label' => 'Contenido',
                'attribute' => 'lec_contenido',
                'format' => 'html',
            ],
            [
                'label' => 'Tipo de Lección',
                'attribute' => 'lec_tipo',
            ],
            [
                'label' => 'Orden',
                'attribute' => 'lec_orden',
            ],
            [
                'label' => 'Estado',
                'attribute' => 'lec_estado',
            ],
            [
                'label' => 'Slug',
                'attribute' => 'lec_slug',
                'format' => 'text',
            ],
            [
                'label' => 'Módulo',
                'attribute' => 'lec_mod_id',
                'value' => $model->modulo->mod_titulo ?? 'No asignado',
            ],
            [
                'label' => 'Curso',
                'value' => $model->modulo->curso->cur_titulo ?? 'No asignado',
            ],
            [
                'label' => 'Ícono de la Lección',
                'format' => 'raw',
                'value' => function ($model) {
                    $iconData = explode('|', $model->lec_icono ?? 'bi-book|#000000');
                    return "<i class='{$iconData[0]}' style='font-size: 2rem; color: {$iconData[1]};'></i>";
                },
            ],
            [
                'attribute' => 'lec_imagen',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'lec_imagen',
                        'htmlOptions' => [
                            'loading' => 'lazy',
                        ],
                    ]);
                },
            ],
        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>

</div>