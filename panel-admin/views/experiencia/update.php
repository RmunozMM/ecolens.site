<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Experiencias $model */

$titulo = $model->exp_cargo . ($model->exp_empresa ? " - " . $model->exp_empresa : "");

$this->title = 'Actualizar Experiencias: ' . $titulo;
$this->params['breadcrumbs'][] = ['label' => 'Experiencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->exp_cargo, 'url' => ['view', 'exp_id' => $model->exp_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="experiencia-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
