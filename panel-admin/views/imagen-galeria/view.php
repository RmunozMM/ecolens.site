<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;

/** @var yii\web\View $this */
/** @var app\models\ImagenesGaleria $model */

$this->title = $model->img_id;
$this->params['breadcrumbs'][] = ['label' => 'Imágenes Galerías', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="imagenes-galeria-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'img_id' => $model->img_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'img_id' => $model->img_id], [
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
            'img_id',
            'img_gal_id',
            'img_ruta',
            'img_descripcion:ntext',
            'img_estado',
        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>

</div>