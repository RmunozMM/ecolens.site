<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CategoriaServicio $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="categoria-servicios-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cas_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cas_publicada')->dropDownList(
        ['SI' => 'SI', 'NO' => 'NO'],
        ['prompt' => '']
    ) ?>

    <div class="form-group btn_save">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>