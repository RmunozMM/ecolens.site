<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\Especie $model */

$this->title = $model->esp_nombre_cientifico;
$this->params['breadcrumbs'][] = ['label' => 'Especies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div class="especie-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'esp_id' => $model->esp_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'esp_id' => $model->esp_id], [
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
            'esp_id',
            'esp_nombre_cientifico',
            'esp_slug',
            'esp_nombre_comun',
            'esp_tax_id',
            [
                'attribute' => 'esp_descripcion',
                'format' => 'html',
                'label' => 'Descripción',
            ],
            [
                'attribute' => 'esp_imagen',
                'label' => 'Imagen',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'esp_imagen',
                    ]);
                },
            ],
            'esp_estado',
        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>

</div>