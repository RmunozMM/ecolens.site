<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\Curso $model */

$this->title = $model->cur_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Cursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="curso-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'cur_id' => $model->cur_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'cur_id' => $model->cur_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de que deseas eliminar este curso?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php
    // Separar el ícono del color usando el separador "|"
    $iconData = explode('|', $model->cur_icono);
    $iconClass = $iconData[0] ?? 'bi-house'; // Ícono predeterminado si falta
    $iconColor = $iconData[1] ?? '#000000'; // Color predeterminado si falta
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'cur_id',
            [
                'label' => 'Ícono',
                'attribute' => 'cur_icono',
                'format' => 'raw',
                'value' => function ($model) use ($iconClass, $iconColor) {
                    return '<i class="' . $iconClass . '" style="font-size: 2rem; color: ' . $iconColor . ';"></i>';
                },
            ],
            'cur_titulo',
            [
                'attribute' => 'cur_descripcion',
                'label' => 'Descripción General',
                'format' => 'html',
            ],
            [
                'attribute' => 'cur_imagen',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'cur_imagen',
                        'htmlOptions' => [
                            'loading' => 'lazy',
                        ],
                    ]);
                },
            ],
            'cur_estado',
            'cur_slug:ntext',
        ], AuditoriaGridColumns::getAuditoriaAttributes($model))
    ]) ?>

</div>