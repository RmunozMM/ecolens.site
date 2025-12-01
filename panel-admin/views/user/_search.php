<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="users-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'usu_id') ?>

    <?= $form->field($model, 'usu_username') ?>

    <?= $form->field($model, 'usu_email') ?>

    <?= $form->field($model, 'usu_password') ?>

    <?= $form->field($model, 'usu_authKey') ?>

    <?php // echo $form->field($model, 'usu_accessToken') ?>

    <?php // echo $form->field($model, 'usu_activate') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
