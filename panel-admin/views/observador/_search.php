<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ObservadorSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="observador-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'obs_id') ?>

    <?= $form->field($model, 'obs_nombre') ?>

    <?= $form->field($model, 'obs_email') ?>

    <?= $form->field($model, 'obs_institucion') ?>

    <?= $form->field($model, 'obs_experiencia') ?>

    <?php // echo $form->field($model, 'obs_pais') ?>

    <?php // echo $form->field($model, 'obs_ciudad') ?>

    <?php // echo $form->field($model, 'obs_estado') ?>

    <?php // echo $form->field($model, 'obs_fecha_registro') ?>

    <?php // echo $form->field($model, 'obs_foto') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
