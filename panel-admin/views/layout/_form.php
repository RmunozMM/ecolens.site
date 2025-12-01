<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Layouts $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="layouts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'lay_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lay_ruta_imagenes')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lay_estado')->dropDownList([ 'activo' => 'Activo', 'inactivo' => 'Inactivo', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
