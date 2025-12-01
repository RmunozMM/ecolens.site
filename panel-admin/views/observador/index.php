<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\widgets\ExportRecordsWidget;
use app\widgets\MassUploadWidget;
use app\widgets\CrudActionButtons;
use app\helpers\AuditoriaGridColumns;
use app\models\Observador;

/** @var yii\web\View $this */
/** @var app\models\ObservadorSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Observadores';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="observador-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="d-flex justify-content-between">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Observador', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Observador',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Observador',
                'modelLabel' => 'Observadores',
                'fieldsMap'  => [
                    'Nombre' => 'obs_nombre',
                    'Email' => 'obs_email',
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

            'obs_id',

            'obs_nombre',

            'obs_email:email',

            'obs_institucion',

            [
                'attribute' => 'obs_experiencia',
                'filter' => [
                    'principiante' => 'Principiante',
                    'aficionado' => 'Aficionado',
                    'experto' => 'Experto',
                    'institucional' => 'Institucional',
                ]
            ],

            [
                'attribute' => 'obs_pais',
                'label' => 'País'
            ],

            [
                'attribute' => 'obs_ciudad',
                'label' => 'Ciudad'
            ],

            [
                'attribute' => 'obs_estado',
                'filter' => [
                    'activo' => 'Activo',
                    'inactivo' => 'Inactivo',
                    'pendiente' => 'Pendiente',
                ],
                'label' => 'Estado'
            ],

            [
                'attribute' => 'obs_fecha_registro',
                'label' => 'Registro',
                'format' => ['date', 'php:d-m-Y H:i']
            ],

            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedAt(),

            CrudActionButtons::column([
                'actions' => ['view', 'update', 'delete'],
                'idAttribute' => 'obs_id',
                'nombreRegistro' => 'observador',
            ])
        ],
    ]); ?>

</div>