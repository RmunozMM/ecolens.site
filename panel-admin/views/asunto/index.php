<?php

use app\models\Asunto;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\widgets\CrudActionButtons;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\AsuntoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Asuntos de formulario de contacto';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asuntos-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <div class="d-flex justify-content-between">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Asunto', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Asunto',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Asunto',
                'modelLabel' => 'Asuntos de formulario de contacto', // Personaliza el nombre
                'fieldsMap'  => [
                    'Nombre'     => 'asu_nombre',
                    'Publicado'  => 'asu_publicado',
                ],
                'uploadUrl' => ['import/index'],
            ]) ?>
        </div>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            'asu_nombre',
            [
                'attribute' => 'asu_publicado',
                'label'     => '¿Publicado?',
                'value'     => function ($model) {
                    return $model->asu_publicado;
                },
                'filter' => ['SI' => 'SI', 'NO' => 'NO'],
            ],
            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),
            CrudActionButtons::column([
                'actions'      => ['view', 'update', 'delete', 'manageGaleria', 'duplicate'],
                'idAttribute'  => 'asu_id',
            ])
        ],
    ]); ?>
</div>