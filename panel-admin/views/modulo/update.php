<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Modulo $model */

$this->title = 'Actualizar Módulo: ' . $model->mod_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Modulos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mod_titulo, 'url' => ['view', 'mod_id' => $model->mod_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="modulo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
