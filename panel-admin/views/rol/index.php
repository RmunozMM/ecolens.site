<?php

use app\models\Rol;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\widgets\CrudActionButtons;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\RolSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Roles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="roles-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Crear Roles', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'rol_nombre',
            'rol_descripcion',
            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),
            CrudActionButtons::column([
                'actions'      => ['view', 'update', 'delete', 'manageGaleria', 'duplicate'],
                'idAttribute'  => 'rol_id',
                'nombreRegistro' => 'rol',                
            ])
        ],
    ]); ?>


</div>
