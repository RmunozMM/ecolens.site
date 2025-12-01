<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\Experiencias $model */

$titulo = $model->exp_cargo . ($model->exp_empresa ? " - " . $model->exp_empresa : "");

$this->title = $titulo;
$this->params['breadcrumbs'][] = ['label' => 'Experiencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="experiencia-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Actualizar', ['update', 'exp_id' => $model->exp_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'exp_id' => $model->exp_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de querer eliminar este ítem? Esta acción no se puede deshacer.',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'exp_id',
            'exp_cargo',
            'exp_empresa',
            'exp_fecha_inicio',
            'exp_fecha_fin',
            [
                'attribute' => 'exp_descripcion',
                'format' => 'html',
            ],
            [
                'attribute' => 'exp_logros',
                'format' => 'html',
            ],
            'exp_publicada',
            [
                'label' => 'Modalidad de Experiencia',
                'value' => $model->modalidad->mod_nombre ?? '—',
            ],
        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>

</div>