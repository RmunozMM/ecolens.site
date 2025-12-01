<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CursoSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="curso-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cur_id') ?>

    <?= $form->field($model, 'cur_titulo') ?>

    <?= $form->field($model, 'cur_descripcion') ?>

    <?= $form->field($model, 'cur_imagen') ?>

    <?= $form->field($model, 'cur_estado') ?>

    <?php // echo $form->field($model, 'cur_slug') ?>

    <?php // echo $form->field($model, 'cur_fecha_creacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
