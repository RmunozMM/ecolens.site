<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Especie $model */

$this->title = 'Actualizar Especie: ' . $model->esp_nombre_comun;
$this->params['breadcrumbs'][] = ['label' => 'Especies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->esp_nombre_comun, 'url' => ['view', 'esp_id' => $model->esp_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="especie-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>