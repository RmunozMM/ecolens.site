<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\widgets\ExportRecordsWidget;
use app\widgets\MassUploadWidget;
use app\widgets\CrudActionButtons;
use app\helpers\AuditoriaGridColumns;
use app\models\Users;
use app\models\Dispositivo;

/** @var yii\web\View $this */
/** @var app\models\DispositivoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Dispositivos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="dispositivo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="d-flex justify-content-between">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Dispositivo', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Dispositivo',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Dispositivo',
                'modelLabel' => 'Dispositivos',
                'fieldsMap'  => [
                    'Tipo' => 'dis_tipo',
                    'Navegador' => 'dis_navegador',
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

            'dis_id',

            [
                'attribute' => 'dis_tipo',
                'filter' => [
                    'desktop' => 'Desktop',
                    'mobile' => 'Mobile',
                    'tablet' => 'Tablet',
                    'camera' => 'Camera',
                    'api' => 'API',
                ]
            ],

            [
                'attribute' => 'dis_sistema_operativo',
                'filter' => [
                    'Windows' => 'Windows',
                    'macOS' => 'macOS',
                    'Linux' => 'Linux',
                    'Android' => 'Android',
                    'iOS' => 'iOS',
                    'Otro' => 'Otro'
                ]
            ],

            [
                'attribute' => 'dis_navegador',
                'filter' => [
                    'Chrome' => 'Chrome',
                    'Safari' => 'Safari',
                    'Firefox' => 'Firefox',
                    'Edge' => 'Edge',
                    'Otro' => 'Otro'
                ]
            ],

            'dis_user_agent',

            'dis_ip_origen',

            [
                'attribute' => 'dis_usuario_id',
                'label' => 'Usuario',
                'value' => function ($model) {
                    return $model->usuario ? $model->usuario->username : '(no asignado)';
                },
                'filter' => \yii\helpers\ArrayHelper::map(Users::find()->all(), 'id', 'username'),
            ],

            AuditoriaGridColumns::createdAt(),

            CrudActionButtons::column([
                'actions' => ['view', 'update', 'delete'],
                'idAttribute' => 'dis_id',
                'nombreRegistro' => 'dispositivo'
            ])
        ],
    ]); ?>

</div>