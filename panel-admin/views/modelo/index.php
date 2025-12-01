<?php

use app\models\Modelo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ModeloSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Modelos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modelo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Modelo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'mod_id',
            'mod_nombre',
            'mod_version',
            'mod_archivo',
            'mod_dataset',
            //'mod_precision_val',
            //'mod_fecha_entrenamiento',
            //'mod_estado',
            //'mod_notas:ntext',
            //'mod_tipo',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Modelo $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'mod_id' => $model->mod_id]);
                 }
            ],
        ],
    ]); ?>


</div>
