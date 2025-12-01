<?php

use app\models\Especie;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\widgets\ExportRecordsWidget;
use app\widgets\MassUploadWidget;
use app\widgets\CrudActionButtons;
use app\helpers\AuditoriaGridColumns;
use app\widgets\ManageImages\FrontWidget;
use yii\helpers\ArrayHelper;
use app\models\Taxonomia;

/** @var yii\web\View $this */
/** @var app\models\EspecieSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Especies';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="especie-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="d-flex justify-content-between">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Especie', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Especie',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Especie',
                'modelLabel' => 'Especies',
                'fieldsMap'  => [
                    'Nombre Científico' => 'esp_nombre_cientifico',
                    'Nombre Común'      => 'esp_nombre_comun',
                    'Estado'            => 'esp_estado',
                ],
                'uploadUrl' => ['import/index'],
            ]) ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'esp_id',

            'esp_nombre_cientifico',
            'esp_nombre_comun',

            [
                'attribute' => 'esp_tax_id',
                'label'     => 'Taxonomía',
                'value'     => function ($model) {
                    return $model->taxonomia ? $model->taxonomia->tax_nombre : 'No definida';
                },
                'filter' => ArrayHelper::map(Taxonomia::find()->all(), 'tax_id', 'tax_nombre'),
            ],

            [
                'attribute' => 'esp_estado',
                'filter' => [
                    'activo' => 'Activo',
                    'inactivo' => 'Inactivo',
                ],
            ],

            [
                'attribute' => 'esp_imagen',
                'format' => 'raw',
                'value' => function ($model) {
                    return FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'esp_imagen',
                        'htmlOptions' => [
                            'loading' => 'lazy',
                            'style' => 'max-height: 100px;',
                        ],
                    ]);
                },
            ],

            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),

            CrudActionButtons::column([
                'actions' => ['view', 'update', 'delete'],
                'idAttribute' => 'esp_id',
                'nombreRegistro' => 'especie',
            ]),
        ],
    ]); ?>

</div>