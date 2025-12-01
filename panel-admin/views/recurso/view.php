<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\LibreriaHelper;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\Recurso $model */



$this->title = $model->rec_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Recursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="recurso-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'rec_id' => $model->rec_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'rec_id' => $model->rec_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de querer eliminar este recurso?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'rec_id',
            'rec_titulo',
            [
                'attribute' => 'rec_icono',
                'label' => 'Ícono',
                'format' => 'raw',
                'value' => function ($model) {
                    if (!empty($model->rec_icono)) {
                        list($icono, $color) = explode('|', $model->rec_icono);
                        return "<i class='$icono' style='color: $color; font-size: 24px;'></i>";
                    }
                    return '<span class="text-muted">(Sin ícono)</span>';
                },
            ],
            'rec_tipo',
            'rec_url:url',
            [
                'attribute' => 'rec_descripcion',
                'format' => 'html',
            ],
            'rec_estado',
            [
                'attribute' => 'rec_imagen',
                'label' => 'Imagen',
                'format' => 'raw',
                'value' => function ($model) use ($libreria) {
                    return $model->rec_imagen
                        ? Html::img(LibreriaHelper::getRecursos(). "uploads/" . $model->rec_imagen, ['width' => '150px', 'class' => 'img-thumbnail'])
                        : '<span class="text-muted">(Sin imagen)</span>';
                },
            ],
            [
                'label' => 'Lección',
                'value' => function ($model) {
                    return $model->leccion ? $model->leccion->lec_titulo : '<span class="text-muted">(No asignada)</span>';
                },
                'format' => 'html',
            ],
            [
                'label' => 'Módulo',
                'value' => function ($model) {
                    return $model->modulo ? $model->modulo->mod_titulo : '<span class="text-muted">(No asignado)</span>';
                },
                'format' => 'html',
            ],
            [
                'label' => 'Curso',
                'value' => function ($model) {
                    return $model->curso ? $model->curso->cur_titulo : '<span class="text-muted">(No asignado)</span>';
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'rec_imagen',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'rec_imagen',
                        'htmlOptions' => [
                            'loading' => 'lazy',
                        ],
                    ]);
                },
            ],
        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>

</div>