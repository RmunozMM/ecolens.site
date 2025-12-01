<?php

use app\models\Layouts;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\widgets\CrudActionButtons;
use app\helpers\AuditoriaGridColumns;


/** @var yii\web\View $this */
/** @var app\models\LayoutSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Layouts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="layouts-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Layouts', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'lay_id',
            'lay_nombre',
            'lay_ruta_imagenes',
            'lay_estado',
            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),
            CrudActionButtons::column([
                'actions'      => ['view', 'update', 'delete', 'manageGaleria', 'duplicate'],
                'idAttribute'  => 'lay_id',
                'nombreRegistro' => 'layout',                
            ])
        ],
    ]); ?>


</div>
