<?php

use app\models\Modulo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\Curso;
use app\models\Users;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\helpers\AuditoriaGridColumns;
use app\widgets\CrudActionButtons;

/** @var yii\web\View $this */
/** @var app\models\ModuloSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Módulos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modulo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="d-flex justify-content-between">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Módulo', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Modulo',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Modulo',
                'modelLabel' => 'Módulos',
                'fieldsMap'  => [
                    'Título'         => 'mod_titulo',
                    'Estado'         => 'mod_estado',
                    'Slug'           => 'mod_slug',
                    'FechaCreación'  => 'mod_fecha_creacion',
                    'Usuario'        => 'mod_usu_id',
                    'Curso'          => 'mod_cur_id',
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

            // Ícono del módulo
            [
                'attribute' => 'mod_icono',
                'label' => 'Ícono',
                'format' => 'raw',
                'value' => function ($model) {
                    $iconData = explode('|', $model->mod_icono);
                    $iconClass = $iconData[0] ?? 'bi-bookmark';
                    $iconColor = $iconData[1] ?? '#000000';
                    return '<i class="' . $iconClass . '" style="font-size: 1.5rem; color: ' . $iconColor . ';"></i>';
                },
                'filter' => false,
            ],

            // Curso al que pertenece el módulo
            [
                'attribute' => 'mod_cur_id',
                'label' => 'Curso',
                'value' => function ($model) {
                    return $model->curso->cur_titulo;
                },
                'filter' => \yii\helpers\ArrayHelper::map(Curso::find()->all(), 'cur_id', 'cur_titulo'),
            ],
            [
                'attribute' => 'mod_imagen',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'mod_imagen',
                        'htmlOptions' => [
                            'loading' => 'lazy',
                        ],
                    ]);
                },
            ],

            // Título del módulo
            [
                'attribute' => 'mod_titulo',
                'label' => 'Título',
            ],

            // Orden dentro del curso
            [
                'attribute' => 'mod_orden',
                'label' => 'Orden',
            ],

            // Estado del módulo
            [
                'attribute' => 'mod_estado',
                'label' => 'Estado',
            ],

            // Slug del módulo
            [
                'attribute' => 'mod_slug',
                'label' => 'Slug',
            ],
            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),
            CrudActionButtons::column([
                'actions'      => ['view', 'update', 'delete', 'manageGaleria', 'duplicate'],
                'idAttribute'  => 'mod_id',
                'nombreRegistro' => 'módulo',                
            ])
        ],
    ]); ?>

</div>