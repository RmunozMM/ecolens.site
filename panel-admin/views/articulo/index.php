<?php

use app\models\Articulo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\Users;
use app\models\CategoriaArticulo;
use app\widgets\ManageGaleriaButton;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\widgets\CrudActionButtons;
use app\helpers\AuditoriaGridColumns;



/** @var yii\web\View $this */
/** @var app\models\ArticuloSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Artículos';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="articulo-index">

        <h1><?= Html::encode($this->title) ?></h1>

        <div class="d-flex justify-content-between">
            <div>
                <?= Html::a('<i class="fa fa-plus"></i> Crear Artículo', ['create'], ['class' => 'btn btn-success']) ?>
            </div>
            <div>
                <?= ExportRecordsWidget::widget([
                    'modelClass' => 'app\models\Articulo',
                    'exportUrl'  => ['export/index'],
                ]) ?>
                <?= MassUploadWidget::widget([
                    'modelClass' => 'app\models\Articulo',
                    'modelLabel' => 'Artículos', // Personaliza el nombre
                    'fieldsMap'  => [
                        'Título'  => 'art_titulo',
                        'Estado'  => 'art_estado',
                    ],
                    'uploadUrl' => ['import/index'],
                ]) ?>
            </div>
        </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Título',
                'attribute' => 'art_titulo',
            ],
            [
                'attribute' => 'art_categoria_id',
                'label' => 'Categoría',
                'value' => function ($model) {
                    return $model->art_categoria_id ? $model->categoriaArticulo->caa_nombre : 'Sin categoría';
                },
                'filter' => \yii\helpers\ArrayHelper::map(CategoriaArticulo::find()->all(), 'caa_id', 'caa_nombre'),
            ],
            [
                'attribute' => 'art_estado',
                'label' => 'Estado',
                'filter' => ['borrador' => 'Borrador', 'publicado' => 'Publicado'],
            ],
            [
                'attribute' => 'art_destacado',
                'label' => '¿Destacado?',
                'filter' => ['SI' => 'Sí', 'NO' => 'No'],
            ],
            [
                'attribute' => 'art_notificacion',
                'label' => '¿Notificar en Newsletter?',
                'filter' => ['SI' => 'Sí', 'NO' => 'No'],
            ],
            [
                'attribute' => 'art_vistas',
                'label' => 'Vistas',
            ],
            [
                'attribute' => 'art_likes',
                'label' => 'Likes',
            ],
            [
                'attribute' => 'art_imagen',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'art_imagen',
                        'htmlOptions' => [
                            'loading' => 'lazy',
                        ],
                    ]);
                },
            ],
            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),

            CrudActionButtons::column([
                'actions'      => ['view', 'publish', 'update', 'delete', 'manageGaleria', 'duplicate'],
                'idAttribute'  => 'art_id',
                'nombreRegistro' => 'artículo',                
            ])
            
        ],
    ]); ?>

</div>