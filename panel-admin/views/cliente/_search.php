<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ClienteSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="clientes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cli_id') ?>

    <?= $form->field($model, 'cli_nombre') ?>

    <?= $form->field($model, 'cli_email') ?>

    <?= $form->field($model, 'cli_telefono') ?>

    <?= $form->field($model, 'cli_direccion') ?>

    <?php // echo $form->field($model, 'cli_estado') ?>

    <?php // echo $form->field($model, 'cli_logo') ?>

    <?php // echo $form->field($model, 'cli_publicado') ?>

    <?php // echo $form->field($model, 'cli_destacado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
