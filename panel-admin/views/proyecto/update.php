<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Proyecto $model */

$this->title = 'Actualizar Proyecto: ' . $model->pro_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Proyectos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pro_titulo, 'url' => ['view', 'pro_id' => $model->pro_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="proyecto-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
