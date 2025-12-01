<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ImagenGaleriaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="imagenes-galeria-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'img_id') ?>

    <?= $form->field($model, 'img_gal_id') ?>

    <?= $form->field($model, 'img_ruta') ?>

    <?= $form->field($model, 'img_descripcion') ?>

    <?= $form->field($model, 'img_estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
