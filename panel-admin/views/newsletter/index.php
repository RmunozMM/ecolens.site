<?php

use app\models\Newsletter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\helpers\AuditoriaGridColumns;
use app\widgets\CrudActionButtons;

/** @var yii\web\View $this */
/** @var app\models\NewsletterSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Newsletters';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="newsletter-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Encabezado con estilo similar a "Servicios" -->
    <div class="d-flex justify-content-between mb-3">
        <div>
            <!-- Botón para crear un nuevo suscriptor (o Newsletter) -->
            <?= Html::a('<i class="fa fa-plus"></i> Crear Newsletter', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Newsletter',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Newsletter',
                'modelLabel' => 'Newsletters',
                'fieldsMap'  => [
                    'Email'                => 'new_email',
                    'Estado'               => 'new_estado',
                    'Verificado'           => 'new_verificado',
                    'Fecha de Creación'    => 'new_fecha_creacion',
                    'Fecha de Modificación'=> 'new_fecha_modificacion',
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

            'new_id',
            [
                'label'     => 'Email',
                'attribute' => 'new_email',
                'format'    => 'email',
            ],
            'new_estado',
            'new_verificado',
            // 'new_fecha_modificacion', // Descomenta si lo necesitas

            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),
            CrudActionButtons::column([
                'actions'      => ['view', 'update','delete'],
                'idAttribute'  => 'new_id',
                'nombreRegistro' => 'Newsletter',                
            ])
        ],
    ]); ?>
</div>
