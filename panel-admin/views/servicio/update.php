<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Servicio $model */

$this->title = 'Actualizar Servicio: ' . $model->ser_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ser_titulo, 'url' => ['view', 'ser_id' => $model->ser_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'msg' => $msg,
    ]) ?>

</div>
