<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\GaleriaButtonWidget;
use yii\helpers\ArrayHelper;
use app\widgets\IconPicker\IconPickerWidget;

/** @var yii\web\View $this */
/** @var app\models\Servicios $model */
/** @var yii\widgets\ActiveForm $form */

use app\helpers\LibreriaHelper;


?>

<div class="servicio-form">
    <?php if ($msg != null): ?>
    <div class="alert alert-success" role="alert">
        <?= $msg ?>
    </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="form-group btn_save">
            <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
            <?= GaleriaButtonWidget::widget() ?>
        </div>

        <!-- Columna izquierda para la imagen -->
        <div class="col-md-4">
            <?= $form->field($model, 'ser_imagen')->fileInput([
                'class' => 'form-control img-view img-thumbnail mt-4',
            ]) ?>

            <label style="padding-top: 30px;">Imagen actual</label>
            <div class="border p-3 text-center">
                <?= \app\widgets\ManageImages\FrontWidget::widget([
                    'model'    => $model,
                    'atributo' => 'ser_imagen',
                    'htmlOptions' => [
                        'style' => 'max-width: 100%; cursor: pointer;',
                        'loading' => 'lazy',
                    ],
                ]) ?>
            </div>
        </div>
        
        <!-- Columna derecha -->
        <div class="col-md-8 border">
            <div style="padding-top: 10px; padding-bottom: 10px;">

                <?= $form->field($model, 'ser_id')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'ser_titulo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'ser_cat_id')->dropDownList(
                    ArrayHelper::map(\app\models\CategoriaServicio::find()->all(), 'cas_id', 'cas_nombre'), 
                    ['prompt' => 'Selecciona una categoría']
                ) ?>
                <?= $form->field($model, 'ser_publicado')->dropDownList([ 'SI' => 'SI', 'NO' => 'NO' ], ['prompt' => 'Selecciona una opción'])->label('¿Servicio Publicado?') ?>
                <?= $form->field($model, 'ser_destacado')->dropDownList([ 'SI' => 'SI', 'NO' => 'NO' ], ['prompt' => 'Selecciona una opción']) ?>
                <?= $form->field($model, 'ser_resumen')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'ser_cuerpo')->textarea(['rows' => 6, 'id' => 'tinyMCE', 'class' => 'tinymce']) ?>

            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>


