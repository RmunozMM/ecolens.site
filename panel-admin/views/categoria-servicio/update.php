<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CategoriaServicio $model */

$this->title = 'Actualizar Categoría: ' . $model->cas_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Categorías de Servicio', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cas_nombre, 'url' => ['view', 'cas_id' => $model->cas_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="categoria-servicio-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>