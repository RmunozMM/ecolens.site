<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Observador $model */

$this->title = 'Actualizar Observador: ' . $model->obs_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Observadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->obs_nombre, 'url' => ['view', 'obs_id' => $model->obs_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="observador-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>