<?php

use app\models\Correo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\Users;
use app\helpers\AuditoriaGridColumns;
use app\widgets\CrudActionButtons;

/** @var yii\web\View $this */
/** @var app\models\CorreoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Correos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="correo-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'cor_nombre',
            'label' => 'Nombre',
        ],
        [
            'attribute' => 'cor_correo',
            'label' => 'Correo Electrónico',
        ],
        [
            'attribute' => 'cor_asunto',
            'label'     => 'Asunto',
            'value'     => function($model) {
                // $model es instancia de Correo. 
                // Puede que el modelo relacionado aún no exista (null), así que comprobamos:
                return isset($model->asunto)
                    ? $model->asunto->asu_nombre
                    : '(no definido)';
            },
            // Para que el filtro nos permita filtrar por asu_nombre, 
            // podríamos usar el siguiente ‘filter’ (opcional):
            'filter'    => \yii\helpers\ArrayHelper::map(
                \app\models\Asunto::find()
                    ->where(['asu_publicado' => 'SI'])
                    ->orderBy(['asu_nombre' => SORT_ASC])
                    ->asArray()
                    ->all(),
                'asu_id',
                'asu_nombre'
            ),
        ],
        [
            'attribute' => 'cor_mensaje',
            'label' => 'Mensaje',
        ],
        [
            'attribute' => 'cor_fecha_consulta',
            'label' => 'Recibido el',
        ],
        [
            'attribute' => 'cor_estado',
            'label' => 'Estado',
        ],
        [
            'attribute' => 'cor_fecha_respuesta',
            'label' => 'Respondido el',
        ],
        AuditoriaGridColumns::createdBy(),
        AuditoriaGridColumns::createdAt(),
        AuditoriaGridColumns::updatedBy(),
        AuditoriaGridColumns::updatedAt(),
        CrudActionButtons::column([
            'actions'      => ['view', 'update','delete'],
            'idAttribute'  => 'cor_id',
            'nombreRegistro' => 'Correo Electrónico',                
        ])
    ],
]); ?>




</div>
