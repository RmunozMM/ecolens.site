<?php

use app\models\Recurso;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\Leccion;
use app\models\Modulo;
use app\models\Curso;
use app\models\Users;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\widgets\CrudActionButtons;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\RecursoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Recursos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recurso-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="d-flex justify-content-between">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Recurso', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Recurso',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Recurso',
                'modelLabel' => 'Recursos',
                'fieldsMap'  => [
                    'Título'   => 'rec_titulo',
                    'Slug'     => 'rec_slug',
                    'Tipo'     => 'rec_tipo',
                    'URL'      => 'rec_url',
                    'Estado'   => 'rec_estado',
                    'Usuario'  => 'rec_usu_id',
                    'Lección'  => 'rec_lec_id',
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

            'rec_id',
            [
                'attribute' => 'rec_tipo',
                'label' => 'Tipo',
                'filter' => [
                    'video' => 'Video',
                    'documento' => 'Documento',
                    'imagen' => 'Imagen',
                    'enlace' => 'Enlace',
                ],
            ],
            [
                'attribute' => 'rec_url',
                'label' => 'URL',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a('Ver Recurso', $model->rec_url, ['target' => '_blank']);
                },
            ],
            'rec_titulo',
            [
                'attribute' => 'rec_descripcion',
                'label' => 'Descripción',
                'format' => 'ntext',
            ],
            [
                'attribute' => 'rec_estado',
                'label' => 'Estado',
                'filter' => ['activo' => 'Activo', 'inactivo' => 'Inactivo'],
            ],
            [
                'attribute' => 'curso',
                'label' => 'Curso',
                'value' => function ($model) {
                    return $model->leccion->modulo->curso->cur_titulo ?? 'Sin Curso';
                },
                'filter' => \yii\helpers\ArrayHelper::map(Curso::find()->all(), 'cur_titulo', 'cur_titulo'),
            ],
            [
                'attribute' => 'modulo',
                'label' => 'Módulo',
                'value' => function ($model) {
                    return $model->leccion->modulo->mod_titulo ?? 'Sin Módulo';
                },
                'filter' => \yii\helpers\ArrayHelper::map(Modulo::find()->all(), 'mod_titulo', 'mod_titulo'),
            ],
            [
                'attribute' => 'leccion',
                'label' => 'Lección',
                'value' => function ($model) {
                    return $model->leccion->lec_titulo ?? 'Sin Lección';
                },
                'filter' => \yii\helpers\ArrayHelper::map(Leccion::find()->all(), 'lec_titulo', 'lec_titulo'),
            ],
            
            [
                'attribute' => 'rec_imagen',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'rec_imagen',
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
                'idAttribute'  => 'rec_id',
                'nombreRegistro' => 'recurso',                
            ])
        ],
    ]); ?>
</div>