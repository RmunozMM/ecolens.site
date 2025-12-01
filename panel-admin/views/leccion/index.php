<?php

use app\models\Leccion;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\Modulo;
use app\models\Curso;
use app\models\Users;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\helpers\AuditoriaGridColumns;
use app\widgets\CrudActionButtons;

/** @var yii\web\View $this */
/** @var app\models\LeccionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Lecciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leccion-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="d-flex justify-content-between">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Lección', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Leccion',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Leccion',
                'modelLabel' => 'Lecciones',
                'fieldsMap'  => [
                    'Título'         => 'lec_titulo',
                    'Tipo'           => 'lec_tipo',
                    'Estado'         => 'lec_estado',
                    'Slug'           => 'lec_slug',
                    'Módulo'         => 'lec_mod_id',
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
                'label'     => 'Título',
                'attribute' => 'lec_titulo',
            ],
            [
                'label'     => 'Contenido',
                'attribute' => 'lec_contenido',
                'format'    => 'html',
            ],
            [
                'label'     => 'Tipo',
                'attribute' => 'lec_tipo',
            ],
            [
                'label'     => 'Orden',
                'attribute' => 'lec_orden',
            ],
            [
                'label'     => 'Estado',
                'attribute' => 'lec_estado',
            ],
            [
                'label'     => 'Curso',
                'attribute' => 'curso', // Atributo virtual
                'value'     => function ($model) {
                    return $model->modulo->curso->cur_titulo ?? 'Sin Curso';
                },
                'filter'    => \yii\helpers\ArrayHelper::map(Curso::find()->all(), 'cur_titulo', 'cur_titulo'),
            ],
            [
                'label'     => 'Módulo',
                'attribute' => 'modulo', // Atributo virtual
                'value'     => function ($model) {
                    return $model->modulo->mod_titulo ?? 'Sin Módulo';
                },
                'filter'    => \yii\helpers\ArrayHelper::map(Modulo::find()->all(), 'mod_titulo', 'mod_titulo'),
            ],
            [
                'attribute' => 'lec_imagen',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'lec_imagen',
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
                'idAttribute'  => 'lec_id',
                'nombreRegistro' => 'lección',                
            ])
        ],
    ]); ?>

</div>