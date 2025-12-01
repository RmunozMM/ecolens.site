<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Opcion $model */

$this->title = 'Actualizar Opción del Sistema: ' . $model->opc_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Opciones del Sistema', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->opc_nombre, 'url' => ['view', 'opc_id' => $model->opc_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="opcion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
