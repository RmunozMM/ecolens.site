<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\Taxonomia $model */

$this->title = $model->tax_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Taxonomías', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div class="taxonomia-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'tax_id' => $model->tax_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'tax_id' => $model->tax_id], [
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
            'tax_id',
            'tax_nombre',
            'tax_nombre_comun',
            'tax_slug',
            [
                'attribute' => 'tax_descripcion',
                'format' => 'raw', // ✅ permite mostrar HTML
                'label' => 'Descripción',
                'value' => function ($model) {
                    return $model->tax_descripcion ?: '<em>Sin descripción</em>';
                },
            ],
            [
                'attribute' => 'tax_imagen',
                'label' => 'Imagen Representativa',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'tax_imagen',
                    ]);
                },
            ],
            [
                'attribute' => 'tax_estado',
                'value' => function ($model) {
                    return ucfirst($model->tax_estado);
                },
            ],
        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>

</div>