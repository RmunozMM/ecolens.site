<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ProyectoSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="proyecto-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pro_id') ?>

    <?= $form->field($model, 'pro_titulo') ?>

    <?= $form->field($model, 'pro_descripcion') ?>

    <?= $form->field($model, 'pro_resumen') ?>

    <?= $form->field($model, 'pro_slug') ?>

    <?php // echo $form->field($model, 'pro_estado') ?>

    <?php // echo $form->field($model, 'pro_destacado') ?>

    <?php // echo $form->field($model, 'pro_imagen') ?>

    <?php // echo $form->field($model, 'pro_creacion') ?>

    <?php // echo $form->field($model, 'pro_modificacion') ?>

    <?php // echo $form->field($model, 'pro_usu_id') ?>

    <?php // echo $form->field($model, 'pro_ser_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
