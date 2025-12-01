<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\CategoriaArticulo $model */

$this->title = $model->caa_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Categoría de Artículos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="categoria-articulo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'caa_id' => $model->caa_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'caa_id' => $model->caa_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estas seguro de querer eliminar este ítem?. Esta acción no se puede deshacer',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'caa_id',
            'caa_nombre',
            'caa_slug',
            'caa_estado',
        ], AuditoriaGridColumns::getAuditoriaAttributes($model))
    ]) ?>

</div>