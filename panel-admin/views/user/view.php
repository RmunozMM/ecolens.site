<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\Users $model */

$this->title = $model->usu_username;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="users-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php if ($model->usu_id != 1): ?>
        <p>
            <?= Html::a('Actualizar', ['update', 'usu_id' => $model->usu_id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Eliminar', ['delete', 'usu_id' => $model->usu_id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => '¿Estás seguro de querer eliminar este usuario? Esta acción no se puede deshacer.',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'usu_id',
            'usu_username',
            [
                'label' => 'Nombre Completo',
                'value' => function ($model) {
                    return $model->usu_nombres . ' ' . $model->usu_apellidos;
                },
            ],
            'usu_telefono',
            'usu_email:email',
            [
                'attribute' => 'usu_activate',
                'label' => '¿Activo?',
                'value' => $model->usu_activate,
            ],
            [
                'label' => 'Rol',
                'value' => function ($model) {
                    return $model->rol->rol_nombre ?? 'Sin rol asignado';
                },
            ],
            [
                'attribute' => 'usu_imagen',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'usu_imagen',
                        'htmlOptions' => [
                            'loading' => 'lazy',
                        ],
                    ]);
                },
            ],
        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>

</div>