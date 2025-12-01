<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ExperienciaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="experiencia-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'exp_id') ?>

    <?= $form->field($model, 'exp_cargo') ?>

    <?= $form->field($model, 'exp_empresa') ?>

    <?= $form->field($model, 'exp_fecha_inicio') ?>

    <?= $form->field($model, 'exp_fecha_fin') ?>

    <?php // echo $form->field($model, 'exp_descripcion') ?>

    <?php // echo $form->field($model, 'exp_logros') ?>

    <?php // echo $form->field($model, 'exp_publicada') ?>

    <?php // echo $form->field($model, 'exp_usu_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
