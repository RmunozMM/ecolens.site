<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\Correo $model */

$this->title = 'Ver Correo Número: ' . $model->cor_id . ' Enviado por ' . $model->cor_nombre . ' enviado el ' . $model->cor_fecha_consulta;
$this->params['breadcrumbs'][] = ['label' => 'Correos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="correo-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Actualizar', ['update', 'cor_id' => $model->cor_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'cor_id' => $model->cor_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de querer eliminar este ítem? Esta acción no se puede deshacer',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'cor_id',
            'cor_nombre',
            'cor_correo',
            'cor_asunto',
            'cor_mensaje:ntext',
            'cor_fecha_consulta',
            'cor_fecha_respuesta',
            'cor_estado',
        ], AuditoriaGridColumns::getAuditoriaAttributes($model))
    ]) ?>

</div>