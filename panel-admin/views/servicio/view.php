<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Users;
use app\helpers\AuditoriaGridColumns;
use app\helpers\LibreriaHelper;



/** @var yii\web\View $this */
/** @var app\models\Servicio $model */

$this->title = $model->ser_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="servicio-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Actualizar', ['update', 'ser_id' => $model->ser_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'ser_id' => $model->ser_id], [
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
            'ser_id',
            'ser_titulo',
            'ser_slug',
            'ser_publicado',
            'ser_destacado',
            'ser_resumen',
            [
                'label' => 'Categoría',
                'value' => $model->categoriaServicio->cas_nombre ?? 'Sin categoría',
            ],
            [
                'attribute' => 'ser_imagen',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'ser_imagen',
                        'htmlOptions' => [
                            'loading' => 'lazy',
                        ],
                    ]);
                },
            ],
            [
                'attribute' => 'ser_icono',
                'label' => 'Ícono',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->ser_icono) {
                        list($iconClass, $iconColor) = explode('|', $model->ser_icono . '|#000000');
                        return Html::tag('i', '', [
                            'class' => $iconClass,
                            'style' => "font-size: 3rem; color: $iconColor;",
                        ]);
                    }
                    return '<span class="text-muted">Sin ícono</span>';
                },
            ],
            [
                'attribute' => 'ser_cuerpo',
                'format' => 'html',
            ],
        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>

</div>