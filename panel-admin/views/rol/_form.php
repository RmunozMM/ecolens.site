<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Roles $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="roles-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group btn_save">
        <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
    </div>

    <?= $form->field($model, 'rol_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rol_descripcion')->textInput(['maxlength' => true]) ?>



    <?php ActiveForm::end(); ?>

</div>
