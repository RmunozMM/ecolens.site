<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\widgets\ExportRecordsWidget;
use app\widgets\MassUploadWidget;
use app\widgets\CrudActionButtons;
use app\helpers\AuditoriaGridColumns;
use app\models\Taxonomia;

/** @var yii\web\View $this */
/** @var app\models\TaxonomiaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Taxonomías';
$this->params['breadcrumbs'][] = $this->title;

// placeholder por si no hay imagen
$placeholder = Yii::getAlias('@web/recursos/img/placeholder.png');
?>
<div class="taxonomia-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="d-flex justify-content-between mb-3">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Taxonomía', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Taxonomia',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Taxonomia',
                'modelLabel' => 'Taxonomías',
                'fieldsMap'  => [
                    'Nombre'          => 'tax_nombre',
                    'Nombre Común'    => 'tax_nombre_comun',
                    'Estado'          => 'tax_estado',
                ],
                'uploadUrl' => ['import/index'],
            ]) ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered align-middle'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tax_nombre',
            'tax_nombre_comun',

            [
                'attribute' => 'tax_imagen',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'tax_imagen',
                        'htmlOptions' => [
                            'loading' => 'lazy',
                        ],
                    ]);
                },
            ],

            [
                'attribute' => 'tax_estado',
                'filter' => [
                    'activo'   => 'Activo',
                    'inactivo' => 'Inactivo',
                ],
                'value' => function ($model) {
                    return $model->tax_estado === 'activo' ? 'Activo' : 'Inactivo';
                }
            ],

            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),

            CrudActionButtons::column([
                'actions'        => ['view', 'update', 'delete'],
                'idAttribute'    => 'tax_id',
                'nombreRegistro' => 'taxonomía',
            ]),
        ],
    ]); ?>

</div>
