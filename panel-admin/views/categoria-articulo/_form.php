<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CategoriaArticulo $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="categoria-articulo-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?= $form->field($model, 'caa_nombre')->textInput(['maxlength' => true, 'placeholder' => 'Nombre de la categoría']) ?>

    <?= $form->field($model, 'caa_estado')->dropDownList([ 
        'publicado' => 'Publicado', 
        'borrador' => 'Borrador' 
    ], ['prompt' => 'Seleccione el estado']) ?>

    <?php ActiveForm::end(); ?>

</div>