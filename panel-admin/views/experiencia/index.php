<?php

use app\models\Experiencias;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\Modalidad;
use app\models\Users;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\helpers\AuditoriaGridColumns;
use app\widgets\CrudActionButtons;


/** @var yii\web\View $this */
/** @var app\models\ExperienciaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Experiencias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="experiencia-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <!-- Encabezado con dos columnas: izquierda para el botón de crear y derecha para exportar y carga masiva -->
    <div class="d-flex justify-content-between">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Experiencia', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Experiencia',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Experiencia',
                'modelLabel' => 'Experiencias',
                'fieldsMap'  => [
                    'Cargo'     => 'exp_cargo',
                    'Publicada' => 'exp_publicada',
                ],
                'uploadUrl' => ['import/index'],
            ]) ?>
        </div>
    </div>

    <!-- GridView con columna de acciones de max-width 200px -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label'     => 'Cargo',
                'attribute' => 'exp_cargo',
            ],
            [
                'label'     => 'Empresa',
                'attribute' => 'exp_empresa',
            ],
            [
                'label'     => 'Inicio',
                'attribute' => 'exp_fecha_inicio',
            ],
            [
                'label'     => 'Fin',
                'attribute' => 'exp_fecha_fin',
            ],
            [
                'attribute' => 'exp_mod_id',
                'label'     => 'Modalidad de la Experiencia',
                'value'     => function ($model) {
                    return $model->modalidad->mod_nombre;
                },
                'filter' => \yii\helpers\ArrayHelper::map(Modalidad::find()->all(), 'mod_id', 'mod_nombre'),
            ],
            [
                'attribute' => 'exp_publicada',
                'label'     => '¿Publicada?',
                'value'     => function ($model) {
                    return $model->exp_publicada;
                },
                'filter'    => ['SI' => 'SI', 'NO' => 'NO'],
            ],
            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),
            CrudActionButtons::column([
                'actions'      => ['view', 'update', 'delete', 'manageGaleria', 'duplicate'],
                'idAttribute'  => 'exp_id',
                'nombreRegistro' => 'experiencia',                
            ])
        ],
    ]); ?>

</div>