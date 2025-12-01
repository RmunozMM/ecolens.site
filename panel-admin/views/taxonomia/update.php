<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Taxonomia $model */

$this->title = 'Actualizar Taxonomía: ' . $model->tax_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Taxonomías', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tax_nombre, 'url' => ['view', 'tax_id' => $model->tax_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="taxonomia-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>