<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;
use app\helpers\LibreriaHelper;



/** @var yii\web\View $this */
/** @var app\models\Proyecto $model */

$this->title = $model->pro_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Proyectos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="proyectos-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'pro_id' => $model->pro_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'pro_id' => $model->pro_id], [
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
            'pro_titulo',
            [
                'label' => 'Cliente',
                'value' => function ($model) {
                    return $model->cliente ? $model->cliente->cli_nombre : 'Sin cliente asignado';
                },
            ],
            'pro_resumen',
            'pro_slug',
            'pro_estado',
            'pro_destacado',
            [
                'label' => 'Servicio',
                'value' => function ($model) {
                    return $model->servicio ? $model->servicio->ser_titulo : 'Sin servicio asignado';
                },
            ],
            [
                'label' => 'Categoría',
                'value' => function ($model) {
                    return $model->categoria ? $model->categoria->cas_nombre : 'Sin categoría asignada';
                },
            ],
            [
                'attribute' => 'pro_imagen',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'pro_imagen',
                        'htmlOptions' => [
                            'loading' => 'lazy',
                        ],
                    ]);
                },
            ],
            [
                'attribute' => 'pro_descripcion',
                'format' => 'html',
            ],
        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>

</div>