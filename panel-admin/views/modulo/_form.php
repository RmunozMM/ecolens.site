<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\helpers\LibreriaHelper;
use app\models\Curso;
use app\widgets\GaleriaButtonWidget;
use app\widgets\iconpicker\IconPickerWidget;



/** @var yii\web\View $this */
/** @var app\models\Modulo $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="modulo-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="form-group btn_save">
            <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
            <?= GaleriaButtonWidget::widget() ?>
        </div>

        <!-- Columna izquierda para la imagen -->
        <div class="col-md-4">
            <?= $form->field($model, 'mod_imagen')->fileInput([
                'class' => 'form-control img-view img-thumbnail mt-4',
            ]) ?>

            <label style="padding-top: 30px;">Imagen actual</label>
            <div class="border p-3 text-center">
                <?= \app\widgets\ManageImages\FrontWidget::widget([
                    'model'    => $model,
                    'atributo' => 'mod_imagen',
                    'htmlOptions' => [
                        'style' => 'max-width: 100%; cursor: pointer;',
                        'loading' => 'lazy',
                    ],
                ]) ?>
            </div>

            <!-- Widget de selección de íconos -->
            <?= IconPickerWidget::widget(['model' => $model, 'attribute' => 'mod_icono']) ?>
        </div>

        <!-- Columna derecha: Datos del módulo -->
        <div class="col-md-8 border">
            <div style="padding-top: 10px; padding-bottom: 10px;">
                
                <!-- Estado del módulo -->
                <?= $form->field($model, 'mod_estado')->dropDownList(
                    ['borrador' => 'Borrador', 'publicado' => 'Publicado'], 
                    ['prompt' => 'Seleccione estado']
                ) ?>

                <!-- Curso al que pertenece -->
                <?= $form->field($model, 'mod_cur_id')->dropDownList(
                    ArrayHelper::map(Curso::find()->all(), 'cur_id', 'cur_titulo'), 
                    ['prompt' => 'Seleccione un curso']
                ) ?>

                <!-- Título del módulo -->
                <?= $form->field($model, 'mod_titulo')->textInput(['maxlength' => true]) ?>

                <!-- Descripción del módulo -->
                <?= $form->field($model, 'mod_descripcion')->textarea(['rows' => 6, 'id' => 'tinyMCE' , 'class' => 'tinymce']) ?>

                <!-- Orden del módulo -->
                <?= $form->field($model, 'mod_orden')->textInput(['type' => 'number', 'min' => 1]) ?>


            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>