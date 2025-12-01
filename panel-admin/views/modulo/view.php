<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\Modulo $model */

$this->title = $model->mod_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Módulos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="modulo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'mod_id' => $model->mod_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'mod_id' => $model->mod_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de querer eliminar este módulo? Esta acción no se puede deshacer.',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'mod_id',
            'mod_titulo',
            [
                'attribute' => 'mod_icono',
                'label' => 'Ícono del Módulo',
                'format' => 'raw',
                'value' => function ($model) {
                    $iconData = explode('|', $model->mod_icono ?? 'bi-house|#000000');
                    $iconClass = $iconData[0];
                    $iconColor = $iconData[1];
                    return "<i class='$iconClass' style='font-size:2rem; color:$iconColor;'></i>";
                },
            ],
            'mod_orden',
            'mod_estado',
            [
                'attribute' => 'mod_slug',
                'label' => 'Slug',
                'format' => 'text',
            ],
            [
                'attribute' => 'mod_descripcion',
                'label' => 'Descripción',
                'format' => 'html',
            ],
            [
                'attribute' => 'mod_cur_id',
                'label' => 'Curso al que pertenece',
                'value' => function ($model) {
                    return $model->curso->cur_titulo ?? '<span class="text-muted">(No asignado)</span>';
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'mod_imagen',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'mod_imagen',
                        'htmlOptions' => [
                            'loading' => 'lazy',
                        ],
                    ]);
                },
            ],
        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>

</div>