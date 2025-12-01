<?php

use app\models\Cliente;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\helpers\LibreriaHelper;
use app\models\Users;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\widgets\CrudActionButtons;
use app\widgets\ManageImages\FrontWidget;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\ClienteSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;



?>
<div class="cliente-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <div class="d-flex justify-content-between">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Cliente', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Cliente',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Cliente',
                'modelLabel' => 'Clientes',
                'fieldsMap'  => [
                    'Nombre'       => 'cli_nombre',
                    'Email'        => 'cli_email',
                    'Estado'       => 'cli_estado',
                    'Publicado'    => 'cli_publicado',
                    'Slug'         => 'cli_slug',
                    'Creación'     => 'cli_creacion',
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
            'cli_nombre',
            'cli_email:email',
            'cli_telefono',
            'cli_direccion',
            [
                'attribute' => 'cli_publicado',
                'label'     => '¿Publicado?',
                'value'     => function ($model) {
                    return $model->cli_publicado;
                },
                'filter' => ['SI' => 'SI', 'NO' => 'NO'],
            ],
            [
                'attribute' => 'cli_destacado',
                'label'     => '¿Destacado?',
                'value'     => function ($model) {
                    return $model->cli_destacado;
                },
                'filter' => ['SI' => 'SI', 'NO' => 'NO'],
            ],
            [
                'attribute' => 'cli_logo',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'cli_logo',
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
                'idAttribute'  => 'cli_id',
                'nombreRegistro' => 'Cliente',                
            ])
        ],
    ]); ?>
</div>