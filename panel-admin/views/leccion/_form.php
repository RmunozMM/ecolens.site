<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\helpers\LibreriaHelper;
use app\models\Modulo;
use app\models\Curso;
use app\widgets\GaleriaButtonWidget;
use app\widgets\iconpicker\IconPickerWidget;



/** @var yii\web\View $this */
/** @var app\models\Leccion $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="leccion-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="form-group btn_save">
            <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
            <?= GaleriaButtonWidget::widget() ?>
        </div>

        <!-- Columna izquierda para la imagen -->
        <div class="col-md-4">
            <?= $form->field($model, 'lec_imagen')->fileInput([
                'class' => 'form-control img-view img-thumbnail mt-4',
            ]) ?>

            <label style="padding-top: 30px;">Imagen actual</label>
            <div class="border p-3 text-center">
                <?= \app\widgets\ManageImages\FrontWidget::widget([
                    'model'    => $model,
                    'atributo' => 'lec_imagen',
                    'htmlOptions' => [
                        'style' => 'max-width: 100%; cursor: pointer;',
                        'loading' => 'lazy',
                    ],
                ]) ?>
            </div>

            <!-- Widget de selección de íconos -->
            <?= IconPickerWidget::widget(['model' => $model, 'attribute' => 'lec_icono']) ?>
        </div>

        <!-- Columna derecha -->
        <div class="col-md-8 border">
            <div style="padding-top: 10px; padding-bottom: 10px;">
                
                <?= $form->field($model, 'lec_estado')->dropDownList(
                    ['borrador' => 'Borrador', 'publicado' => 'Publicado'], 
                    ['prompt' => 'Seleccione estado']
                ) ?>
                
                <!-- Selección de módulo con evento AJAX para actualizar el curso -->
                <?= $form->field($model, 'lec_mod_id')->dropDownList(
                    ArrayHelper::map(Modulo::find()->all(), 'mod_id', 'mod_titulo'), 
                    [
                        'prompt' => 'Seleccione un módulo',
                        'onchange' => '
                            let modId = $(this).val();
                            if (modId) {
                                $.ajax({
                                    url: "' . \yii\helpers\Url::to(['modulo/get-info']) . '",
                                    type: "GET",
                                    data: { mod_id: modId },
                                    success: function(data) {
                                        if (data) {
                                            $("#curso-titulo").val(data.curso);
                                        }
                                    }
                                });
                            } else {
                                $("#curso-titulo").val("");
                            }
                        '
                    ]
                ) ?>

                <!-- Campo de solo lectura para mostrar el curso -->
                <?= Html::label('Curso', 'curso-titulo') ?>
                <?= Html::textInput('curso-titulo', $model->modulo->curso->cur_titulo ?? 'Sin curso', [
                    'class' => 'form-control',
                    'readonly' => true,
                    'id' => 'curso-titulo'
                ]) ?>

                <?= $form->field($model, 'lec_titulo')->textInput(['maxlength' => true]) ?>
                
                <?= $form->field($model, 'lec_contenido')->textarea(['rows' => 6, 'id' => 'tinyMCE' , 'class' => 'tinymce']) ?>

                <?= $form->field($model, 'lec_tipo')->dropDownList(
                    ['texto' => 'Texto', 'video' => 'Video', 'documento' => 'Documento', 'enlace' => 'Enlace'],
                    ['prompt' => 'Seleccione el tipo de lección']
                ) ?>

                <?= $form->field($model, 'lec_orden')->textInput(['type' => 'number', 'min' => 1]) ?>

            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>