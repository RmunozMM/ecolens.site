<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CategoriaArticulo $model */

$this->title = 'Actualizar Categoría de Artículos: ' . $model->caa_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Categoría de Artículos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->caa_nombre, 'url' => ['view', 'caa_id' => $model->caa_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="categoria-articulo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
