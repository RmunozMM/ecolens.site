<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Modalidad $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="modalidad-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group btn_save">
        <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
    </div>

    <?= $form->field($model, 'mod_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mod_publicado')->dropDownList([ 'SI' => 'SI', 'NO' => 'NO', ], ['prompt' => '']) ?>



    <?php ActiveForm::end(); ?>

</div>
