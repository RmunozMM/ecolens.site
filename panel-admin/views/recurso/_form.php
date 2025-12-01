<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\LibreriaHelper;
use app\models\Leccion;
use app\models\Modulo;
use app\models\Curso;
use yii\helpers\ArrayHelper;
use app\widgets\GaleriaButtonWidget;
use app\widgets\IconPicker\IconPickerWidget;



/** @var yii\web\View $this */
/** @var app\models\Recurso $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="recurso-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="form-group btn_save">
            <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
            <?= GaleriaButtonWidget::widget() ?>
        </div>

        <!-- Columna izquierda para la imagen -->
        <div class="col-md-4">
            <?= $form->field($model, 'rec_imagen')->fileInput([
                'class' => 'form-control img-view img-thumbnail mt-4',
            ]) ?>

            <label style="padding-top: 30px;">Imagen actual</label>
            <div class="border p-3 text-center">
                <?= \app\widgets\ManageImages\FrontWidget::widget([
                    'model'    => $model,
                    'atributo' => 'rec_imagen',
                    'htmlOptions' => [
                        'style' => 'max-width: 100%; cursor: pointer;',
                        'loading' => 'lazy',
                    ],
                ]) ?>
            </div>

            <!-- Widget de selección de iconos -->
            <?= IconPickerWidget::widget(['model' => $model, 'attribute' => 'rec_icono']) ?>
        </div>

        <!-- Columna derecha -->
        <div class="col-md-8 border">
            <div style="padding-top: 10px; padding-bottom: 10px;">

                <?= $form->field($model, 'rec_titulo')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'Ingrese el título del recurso'
                ]) ?>

                <?= $form->field($model, 'rec_lec_id')->dropDownList(
                    ArrayHelper::map(Leccion::find()->all(), 'lec_id', 'lec_titulo'), 
                    [
                        'prompt' => 'Seleccione una lección',
                        'onchange' => '
                            let lecId = $(this).val();
                            if (lecId) {
                                $.ajax({
                                    url: "' . \yii\helpers\Url::to(['leccion/get-info']) . '",
                                    type: "GET",
                                    data: { lec_id: lecId },
                                    success: function(data) {
                                        if (data) {
                                            $("#modulo-titulo").val(data.modulo);
                                            $("#curso-titulo").val(data.curso);
                                        }
                                    }
                                });
                            } else {
                                $("#modulo-titulo").val("");
                                $("#curso-titulo").val("");
                            }
                        '
                    ]
                ) ?>

                <?= Html::label('Módulo', 'modulo-titulo') ?>
                <?= Html::textInput('modulo-titulo', $model->modulo->mod_titulo ?? 'Sin módulo', [
                    'class' => 'form-control',
                    'readonly' => true,
                    'id' => 'modulo-titulo'
                ]) ?>

                <?= Html::label('Curso', 'curso-titulo') ?>
                <?= Html::textInput('curso-titulo', $model->curso->cur_titulo ?? 'Sin curso', [
                    'class' => 'form-control',
                    'readonly' => true,
                    'id' => 'curso-titulo'
                ]) ?>

                <?= $form->field($model, 'rec_tipo')->dropDownList(
                    ['video' => 'Video', 'documento' => 'Documento', 'imagen' => 'Imagen', 'enlace' => 'Enlace'], 
                    ['prompt' => 'Seleccione tipo de recurso']
                ) ?>

                <?= $form->field($model, 'rec_url')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'rec_descripcion')->textarea(['rows' => 6, 'id' => 'tinyMCE' , 'class' => 'tinymce']) ?>

                <?= $form->field($model, 'rec_estado')->dropDownList(
                    ['activo' => 'Activo', 'inactivo' => 'Inactivo'], 
                    ['prompt' => 'Seleccione estado']
                ) ?>

            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>