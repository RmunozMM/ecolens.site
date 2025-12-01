<?php

use app\models\Modalidad;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\helpers\AuditoriaGridColumns;
use app\widgets\CrudActionButtons;

/** @var yii\web\View $this */
/** @var app\models\ModalidadSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Modalidad de Experiencia';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modalidad-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <div class="d-flex justify-content-between">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Modalidad', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Modalidad',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Modalidad',
                'modelLabel' => 'Modalidad de Experiencia',
                'fieldsMap'  => [
                    'Nombre'    => 'mod_nombre',
                    'Publicado' => 'mod_publicado',
                    'Usuario'   => 'mod_usu_id',
                ],
                'uploadUrl' => ['import/index'],
            ]) ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider'  => $dataProvider,
        'filterModel'   => $searchModel,
        'columns'       => [
            ['class' => 'yii\grid\SerialColumn'],
            'mod_nombre',
            'mod_publicado',
            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),
            CrudActionButtons::column([
                'actions'      => ['view', 'update', 'delete', 'manageGaleria', 'duplicate'],
                'idAttribute'  => 'mod_id',
                'nombreRegistro' => 'modalidad',                
            ])
        ],
    ]); ?>

</div>