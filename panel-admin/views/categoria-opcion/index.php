<?php

use app\models\CategoriaOpcion;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use app\widgets\CrudActionButtons;
use app\helpers\AuditoriaGridColumns;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;


/** @var yii\web\View $this */
/** @var app\models\CategoriaOpcionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Categorías de Opciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categoria-opcion-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php if(Yii::$app->user->identity->usu_rol_id == 1): // Solo SuperAdmin puede crear categorías ?>
        <p>
            <?= Html::a('Crear Categoría de Opción', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'cat_id',
            'cat_nombre',
            'cat_descripcion',
            'cat_icono',
            [
                'attribute' => 'cat_orden',
                'label' => 'Orden',
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),
            CrudActionButtons::column([
                'actions'      => ['view', 'update', 'delete', 'manageGaleria', 'duplicate'],
                'idAttribute'  => 'cat_id',
            ])
        ],
    ]); ?>

</div>