<?php

use app\models\Media;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\helpers\LibreriaHelper;
use app\widgets\CrudActionButtons;
use app\widgets\manageImages\backWidget;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\MediaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Media';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="media-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->identity->usu_rol_id == 1): ?>
        <p>
            <?= Html::a('<i class="fa fa-plus"></i> Crear Media', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            'med_nombre',
            'med_descripcion',
            'med_ruta',
            'med_entidad',
            'med_tipo',
            [
                'attribute' => 'med_ruta',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\widgets\ManageImages\FrontWidget::widget([
                        'model' => $model,
                        'atributo' => 'med_ruta',
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
                'actions'        => ['delete'],
                'idAttribute'    => 'med_id',
                'nombreRegistro' => 'fotografía',
            ]),
        ],
    ]); ?>

</div>
