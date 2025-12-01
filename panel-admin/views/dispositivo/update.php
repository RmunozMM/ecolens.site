<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Dispositivo $model */

$this->title = 'Actualizar Dispositivo: ' . $model->dis_id;
$this->params['breadcrumbs'][] = ['label' => 'Dispositivos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dis_id, 'url' => ['view', 'dis_id' => $model->dis_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="dispositivo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>