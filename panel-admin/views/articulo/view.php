<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;
use app\helpers\LibreriaHelper;



/** @var yii\web\View $this */
/** @var app\models\Articulos $model */

$this->title = $model->art_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Artículos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div class="articulo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'art_id' => $model->art_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'art_id' => $model->art_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estas seguro de querer eliminar este ítem? Esta acción no se puede deshacer',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'art_id',
            'art_titulo',
            'art_slug',
            [
                'attribute' => 'art_categoria_id',
                'value' => $model->categoriaArticulo->caa_nombre,
            ],
            'art_estado',
            'art_destacado',
            'art_fecha_publicacion',
            'art_notificacion',
            [
                'attribute' => 'art_contenido',
                'format' => 'html',
            ],
            [
                'attribute' => 'art_resumen',
                'format' => 'html',
            ],
            'art_etiquetas',
            'art_vistas',
            'art_likes',
            'art_comentarios_habilitados',
            'art_meta_descripcion:ntext',
            [
                'attribute' => 'art_palabras_clave',
                'format' => 'html',
            ],

            [
                'attribute' => 'art_imagen',
                'label' => 'Imagen del Artículo',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'art_imagen',
                    ]);
                },
            ],
        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>

</div>