<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Modelo $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="modelo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mod_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mod_version')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mod_archivo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mod_dataset')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mod_precision_val')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mod_fecha_entrenamiento')->textInput() ?>

    <?= $form->field($model, 'mod_estado')->dropDownList([ 'activo' => 'Activo', 'deprecado' => 'Deprecado', 'en_entrenamiento' => 'En entrenamiento', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'mod_notas')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'mod_tipo')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
