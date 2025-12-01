<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;
use app\helpers\LibreriaHelper;



/** @var yii\web\View $this */
/** @var app\models\Testimonio $model */

$this->title = $model->tes_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Testimonios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="testimonio-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'tes_id' => $model->tes_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'tes_id' => $model->tes_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de querer eliminar este ítem? Esta acción no se puede deshacer.',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'tes_id',
            'tes_nombre',
            'tes_cargo',
            'tes_empresa',
            'tes_orden',
            'tes_estado',
            'tes_slug:ntext',
            [
                'attribute' => 'tes_imagen',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'tes_imagen',
                        'htmlOptions' => [
                            'loading' => 'lazy',
                        ],
                    ]);
                },
            ],
            [
                'attribute' => 'tes_testimonio',
                'format' => 'html',
            ],

        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>

</div>