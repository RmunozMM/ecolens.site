<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\Observador $model */

$this->title = $model->obs_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Observadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div class="observador-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'obs_id' => $model->obs_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'obs_id' => $model->obs_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de que deseas eliminar este ítem? Esta acción no se puede deshacer.',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'obs_id',
            'obs_nombre',
            'obs_email:email',
            'obs_institucion',
            'obs_experiencia',
            'obs_pais',
            'obs_ciudad',
            'obs_estado',
            'obs_fecha_registro',
            [
                'attribute' => 'obs_foto',
                'label' => 'Foto del Observador',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'obs_foto',
                    ]);
                },
            ],
        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>

</div>