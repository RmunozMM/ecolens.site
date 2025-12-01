<?php

use app\models\Proyecto;
use app\models\Servicio;
use app\models\Users;
use app\models\Categorias;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\helpers\LibreriaHelper;
use app\widgets\CrudActionButtons;
use app\helpers\AuditoriaGridColumns;





$this->title = 'Proyectos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proyecto-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="d-flex justify-content-between">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Proyecto', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Proyecto',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Proyecto',
                'modelLabel' => 'Proyectos',
                'fieldsMap'  => [
                    'Título'       => 'pro_titulo',
                    'Slug'         => 'pro_slug',
                    'Estado'       => 'pro_estado',
                    'Fecha Inicio' => 'pro_fecha_inicio',
                    'Fecha Fin'    => 'pro_fecha_fin',
                    'Cliente'      => 'pro_cli_id',
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

            [
                'attribute' => 'pro_titulo',
                'label' => 'Título',
                'filter' => Html::activeTextInput($searchModel, 'pro_titulo', ['class' => 'form-control']),
            ],
            [
                'attribute' => 'pro_resumen',
                'label' => 'Resumen del proyecto',
                'format' => 'html',
                'contentOptions' => ['style' => 'max-width:400px; white-space:normal;'],
            ],
            [
                'attribute' => 'pro_estado',
                'label' => '¿Publicado?',
            ],
            [
                'attribute' => 'pro_ser_id',
                'label' => 'Servicio',
                'value' => function ($model) {
                    return $model->servicio->ser_titulo;
                },
                'filter' => \yii\helpers\ArrayHelper::map(Servicio::find()->all(), 'ser_id', 'ser_titulo'),
            ],
            [
                'attribute' => 'pro_cli_id',
                'label' => 'Cliente',
                'value' => function ($model) {
                    return $model->cliente ? $model->cliente->cli_nombre : 'Sin Cliente';
                },
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\Cliente::find()->all(), 'cli_id', 'cli_nombre'),
            ],
            [
                'attribute' => 'pro_fecha_inicio',
                'label' => 'Fecha de Inicio',
            ],
            [
                'attribute' => 'pro_fecha_fin',
                'label' => 'Fecha de Fin',
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
            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),
            CrudActionButtons::column([
                'actions'      => ['view', 'update', 'delete', 'manageGaleria', 'duplicate'],
                'idAttribute'  => 'pro_id',
                'nombreRegistro' => 'proyecto',                
            ])
        ],
    ]); ?>

</div>