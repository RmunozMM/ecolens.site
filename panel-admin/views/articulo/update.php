<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Articulo $model */

$this->title = 'Actualizar Artículos: ' . $model->art_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Articulos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->art_titulo, 'url' => ['view', 'art_id' => $model->art_id]];

$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="articulo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
 