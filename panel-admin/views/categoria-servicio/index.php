<?php

use app\models\CategoriaServicio;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\helpers\AuditoriaGridColumns;
use app\widgets\CrudActionButtons;

/** @var yii\web\View $this */
/** @var app\models\CategoriaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Categorías de Servicios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categoria-servicio-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <div class="d-flex justify-content-between">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Categoría', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\CategoriaServicio',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\CategoriaServicio',
                'modelLabel' => 'Categorías', // Personaliza el nombre
                'fieldsMap'  => [
                    'Nombre'    => 'cas_nombre',
                    'Publicada' => 'cas_publicada',
                ],
                'uploadUrl' => ['import/index'],
            ]) ?>
        </div>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider'  => $dataProvider,
        'filterModel'   => $searchModel,
        'columns'       => [
            ['class' => 'yii\grid\SerialColumn'],
            'cas_nombre',
            [
                'attribute' => 'cas_publicada',
                'label'     => '¿Publicada?',
                'value'     => function ($model) {
                    return $model->cas_publicada;
                },
                'filter'    => ['SI' => 'SI', 'NO' => 'NO'],
            ],
            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),
            CrudActionButtons::column([
                'actions'      => ['view', 'update', 'delete', 'manageGaleria', 'duplicate'],
                'idAttribute'  => 'cas_id',
                'nombreRegistro' => 'Categoría de Servicio',                
            ])
        ],
    ]); ?>

</div>