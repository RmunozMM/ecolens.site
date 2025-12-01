<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Deteccion $model */

$this->title = 'Actualizar Detección: ' . $model->det_id;
$this->params['breadcrumbs'][] = ['label' => 'Detecciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->det_id, 'url' => ['view', 'det_id' => $model->det_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="deteccion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>