<?php

use app\models\CategoriaArticulo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\widgets\CrudActionButtons;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\CategoriaArticuloSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Categorías de Artículos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categoria-articulo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="d-flex justify-content-between">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Categoría de Artículo', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\CategoriaArticulo',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\CategoriaArticulo',
                'modelLabel' => 'Categorías de Artículos', // Personaliza el nombre
                'fieldsMap'  => [
                    'Nombre' => 'caa_nombre',
                    'Slug'   => 'caa_slug',
                    'Estado' => 'caa_estado',
                ],
                'uploadUrl' => ['import/index'],
            ]) ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            'caa_nombre',
            'caa_slug',

            [
                'attribute' => 'caa_estado',
                'label' => 'estado',
            ],
            CrudActionButtons::column([
                'actions'      => ['view', 'update', 'delete', 'manageGaleria', 'duplicate'],
                'idAttribute'  => 'caa_id',
                'nombreRegistro' => 'categoría de Artículo',                
            ])
        ],
    ]); ?>

</div>