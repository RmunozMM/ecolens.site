<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Trabajador $model */

$this->title = 'Actualizar Trabajador: ' . $model->tra_nombre . " " . $model->tra_apellido;
$this->params['breadcrumbs'][] = ['label' => 'Trabajadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => ($model->tra_nombre . " " . $model->tra_apellido), 'url' => ['view', 'tra_id' => $model->tra_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="trabajador-update">
    <?= $msg ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
