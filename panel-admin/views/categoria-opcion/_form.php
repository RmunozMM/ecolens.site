<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CategoriaOpcion $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="categoria-opcion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cat_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cat_descripcion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cat_icono')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cat_orden')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
