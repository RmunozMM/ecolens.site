<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Asuntos $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="asuntos-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group btn_save">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?= $form->field($model, 'asu_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'asu_publicado')->dropDownList([ 'SI' => 'SI', 'NO' => 'NO', ], ['prompt' => '']) ?>

    <?php ActiveForm::end(); ?>

</div>
