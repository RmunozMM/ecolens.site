<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Modalidad;

/** @var yii\web\View $this */
/** @var app\models\Experiencias $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="experiencia-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- Botón para guardar cambios -->
    <div class="form-group btn_save">
        <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
    </div>

    <!-- Campo oculto para el ID de la experiencia -->
    <?= $form->field($model, 'exp_id')->hiddenInput()->label(false) ?>

    <div class="row">
        <!-- Columna izquierda: campos cortos -->
        <div class="col-md-4">
            <?= $form->field($model, 'exp_cargo')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'exp_empresa')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'exp_mod_id')->dropDownList(
                    Modalidad::find()->select(['mod_nombre', 'mod_id'])->indexBy('mod_id')->column(),
                    ['prompt' => 'Selecciona una modalidad']
                ) ?>
            <?= $form->field($model, 'exp_publicada')->dropDownList(
                    ['SI' => 'SI', 'NO' => 'NO'],
                    ['prompt' => '']
                ) ?>
            <?= $form->field($model, 'exp_fecha_inicio')->textInput([
                    'class' => 'form-control',
                    'id' => 'datepicker'
                ]) ?>
            <?= $form->field($model, 'exp_fecha_fin')->textInput([
                    'class' => 'form-control',
                    'id' => 'datepicker'
                ]) ?>
        </div>

        <!-- Columna derecha: textareas y campos ocultos -->
        <div class="col-md-8 border">
            <div style="padding-top: 10px; padding-bottom: 10px;">
                <?= $form->field($model, 'exp_descripcion')->textarea([
                        'rows' => 6,
                        'id' => 'tinyMCE',
                        'class' => 'tinymce'
                    ]) ?>
                <?= $form->field($model, 'exp_logros')->textarea([
                        'rows' => 6,
                        'id' => 'tinyMCE',
                        'class' => 'tinymce'
                    ]) ?>
                <?= $form->field($model, 'exp_usu_id')->hiddenInput([
                        'value' => Yii::$app->user->identity->usu_id
                    ])->label(false) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>