<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\NewsletterSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="newsletter-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'new_id') ?>

    <?= $form->field($model, 'new_email') ?>

    <?= $form->field($model, 'new_estado') ?>

    <?= $form->field($model, 'new_verificado') ?>

    <?= $form->field($model, 'new_fecha_creacion') ?>

    <?php // echo $form->field($model, 'new_fecha_modificacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
