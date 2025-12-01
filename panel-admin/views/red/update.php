<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Redes $model */

$this->title = 'Update Red: ' . $model->red_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Redes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->red_nombre, 'url' => ['view', 'red_id' => $model->red_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="redes-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
