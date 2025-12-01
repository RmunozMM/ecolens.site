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

        [
            'attribute' => 'med_nombre',
            'headerOptions' => ['style' => 'width:150px;'], // Ajusta el ancho del encabezado
            'contentOptions' => ['style' => 'width:150px; word-wrap: break-word;'], // Ajusta contenido
        ],
        [
            'attribute' => 'med_descripcion',
            'headerOptions' => ['style' => 'width:250px;'],
            'contentOptions' => ['style' => 'width:250px; word-wrap: break-word;'],
        ],
        [
            'attribute' => 'med_tipo',
            'headerOptions' => ['style' => 'width:100px;'],
            'contentOptions' => ['style' => 'width:100px;'],
        ],

        'med_entidad',

        [
            'attribute' => 'med_ruta',
            'format' => 'raw',
            'headerOptions' => ['style' => 'width:120px;'],
            'contentOptions' => ['style' => 'width:120px;'],
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
            'actions'        => ['view', 'update'],
            'idAttribute'    => 'med_id',
            'nombreRegistro' => 'fotografía',
            'headerOptions' => ['style' => 'width:120px;'],
            'contentOptions' => ['style' => 'width:120px;'],
        ]),
    ],
]); ?>


</div>
