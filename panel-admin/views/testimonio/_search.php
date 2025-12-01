<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TestimonioSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="testimonio-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'tes_id') ?>

    <?= $form->field($model, 'tes_nombre') ?>

    <?= $form->field($model, 'tes_cargo') ?>

    <?= $form->field($model, 'tes_empresa') ?>

    <?= $form->field($model, 'tes_testimonio') ?>

    <?php // echo $form->field($model, 'tes_imagen') ?>

    <?php // echo $form->field($model, 'tes_orden') ?>

    <?php // echo $form->field($model, 'tes_estado') ?>

    <?php // echo $form->field($model, 'tes_slug') ?>

    <?php // echo $form->field($model, 'tes_fecha_creacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
