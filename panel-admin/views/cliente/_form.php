<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\LibreriaHelper;


/** @var yii\web\View $this */
/** @var app\models\Cliente $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cliente-form">
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'] // Para la subida de archivos
    ]); ?>
    
    <!-- Muestra errores de validación -->
    <?= $form->errorSummary($model) ?>

    <div class="row">
        <div class="form-group btn_save">
            <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
        </div>
        
        <!-- Columna izquierda para la imagen -->
        <div class="col-md-4">
            <?= $form->field($model, 'cli_logo')->fileInput([
                'class' => 'form-control img-view img-thumbnail mt-4',
            ]) ?>

            <label style="padding-top: 30px;">Imagen actual</label>
            <div class="border p-3 text-center">
                <?= \app\widgets\ManageImages\FrontWidget::widget([
                    'model'    => $model,
                    'atributo' => 'cli_logo',
                    'htmlOptions' => [
                        'style' => 'max-width: 100%; cursor: pointer;',
                        'loading' => 'lazy',
                    ],
                ]) ?>
            </div>
        </div>

        <!-- 📌 Columna derecha para la información del cliente -->
        <div class="col-md-8 border">
            <div style="padding-top:10px;padding-bottom:10px;">
                
                <?= $form->field($model, 'cli_nombre')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'cli_email')->textInput([
                    'maxlength' => true,
                    'type' => 'email',
                    'placeholder' => 'ejemplo@dominio.com',
                ]) ?>

                <?= $form->field($model, 'cli_telefono')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'cli_direccion')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'cli_descripcion')->textarea(['rows' => 6, 'id' => 'tinyMCE' , 'class' => 'tinymce']) ?>

                <?= $form->field($model, 'cli_estado')->dropDownList(
                    ['SI' => 'Activo', 'NO' => 'Inactivo'], ['value' => 'SI']
                ) ?>

                <?= $form->field($model, 'cli_publicado')->dropDownList(
                    ['SI' => 'Publicado', 'NO' => 'No Publicado'], ['value' => 'NO']
                ) ?>

                <?= $form->field($model, 'cli_destacado')->dropDownList(
                    ['SI' => 'Sí', 'NO' => 'No'], ['value' => 'NO']
                ) ?>

                <?= $form->field($model, 'cli_usu_id')->hiddenInput([
                    'value' => Yii::$app->user->identity->usu_id
                ])->label(false) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>