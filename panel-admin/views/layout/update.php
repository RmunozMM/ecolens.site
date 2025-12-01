<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Layouts $model */

$this->title = 'Actualizar Layouts: ' . $model->lay_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Layouts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->lay_nombre, 'url' => ['view', 'lay_id' => $model->lay_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="layouts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
