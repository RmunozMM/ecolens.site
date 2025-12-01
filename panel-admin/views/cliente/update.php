<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Cliente $model */

$this->title = 'Actualizar Clientes: ' . $model->cli_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cli_nombre, 'url' => ['view', 'cli_id' => $model->cli_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="cliente-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
