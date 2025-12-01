<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\CategoriaServicio $model */

$this->title = $model->cas_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Categorías de Servicio', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="categoria-servicio-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Actualizar', ['update', 'cas_id' => $model->cas_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'cas_id' => $model->cas_id], [
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
            'cas_id',
            'cas_nombre',
        ], AuditoriaGridColumns::getAuditoriaAttributes($model))
    ]) ?>

</div>