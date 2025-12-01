<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Menu $model */

$this->title = 'Actualizar Menú: ' . $model->men_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Menús', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->men_nombre, 'url' => ['view', 'men_id' => $model->men_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="menu-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
