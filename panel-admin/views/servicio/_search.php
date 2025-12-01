<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ServicioSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="servicio-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ser_id') ?>

    <?= $form->field($model, 'ser_titulo') ?>

    <?= $form->field($model, 'ser_slug') ?>

    <?= $form->field($model, 'ser_resumen') ?>

    <?= $form->field($model, 'ser_cuerpo') ?>

    <?php // echo $form->field($model, 'ser_publicado') ?>

    <?php // echo $form->field($model, 'ser_destacada') ?>

    <?php // echo $form->field($model, 'ser_creacion') ?>

    <?php // echo $form->field($model, 'ser_modificacion') ?>

    <?php // echo $form->field($model, 'ser_imagen') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
