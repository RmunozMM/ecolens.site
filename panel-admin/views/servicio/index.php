<?php

use app\models\Servicio;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\Users;
use app\models\CategoriaServicio;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\widgets\CrudActionButtons;
use app\widgets\ManageImages\FrontWidget;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\ServicioSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Servicios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicio-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="d-flex justify-content-between">
        <div>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Servicio', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Servicio',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Servicio',
                'modelLabel' => 'Servicios',
                'fieldsMap'  => [
                    'Título'     => 'ser_titulo',
                    'Slug'       => 'ser_slug',
                    'Publicado'  => 'ser_publicado',
                    'Destacado'  => 'ser_destacado',
                    'Categoría'  => 'ser_cat_id',
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
                'label' => 'Título',
                'attribute' => 'ser_titulo',
                'filter' => Html::activeTextInput($searchModel, 'ser_titulo', ['class' => 'form-control']),
            ],
            [
                'attribute' => 'ser_cat_id',
                'label' => 'Categoría',
                'value' => function ($model) {
                    return $model->categoriaServicio 
                        ? $model->categoriaServicio->cas_nombre 
                        : '<span class="text-muted">(No asignado)</span>';
                },
                'filter' => \yii\helpers\ArrayHelper::map(
                    CategoriaServicio::find()->all(),
                    'cas_id',      // Ajusta al nombre de tu PK real
                    'cas_nombre'   // Ajusta al nombre de tu columna de nombre
                ),
                'format' => 'html',
            ],
            [
                'label' => '¿Destacado?',
                'attribute' => 'ser_destacado',
                'value' => function ($model) {
                    return $model->ser_destacado ?: '<span class="text-muted">(no definido)</span>';
                },
                'filter' => ['SI' => 'Sí', 'NO' => 'No'],
                'format' => 'html',
            ],
            [
                'attribute' => 'ser_icono',
                'label' => 'Ícono',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->ser_icono) {
                        list($iconClass, $iconColor) = explode('|', $model->ser_icono . '|#000000');
                        return Html::tag('i', '', [
                            'class' => $iconClass,
                            'style' => "font-size: 1.5rem; color: $iconColor;",
                        ]);
                    }
                    return '<span class="text-muted">Sin ícono</span>';
                },
                'filter' => false,
            ],
            [
                'attribute' => 'ser_imagen',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'ser_imagen',
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
                'idAttribute'  => 'ser_id',
                'nombreRegistro' => 'servicio',                
            ])
        ],
    ]); ?>
</div>