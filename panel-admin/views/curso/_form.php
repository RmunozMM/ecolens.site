<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\LibreriaHelper;
use app\widgets\GaleriaButtonWidget;
use app\widgets\iconpicker\IconPickerWidget;



/** @var yii\web\View $this */
/** @var app\models\Curso $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="curso-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="form-group btn_save">
            <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
            <?= GaleriaButtonWidget::widget() ?>
        </div>

        <!-- Columna izquierda para la imagen -->
        <div class="col-md-4">
            <?= $form->field($model, 'cur_imagen')->fileInput([
                'class' => 'form-control img-view img-thumbnail mt-4',
            ]) ?>

            <label style="padding-top: 30px;">Imagen actual</label>
            <div class="border p-3 text-center">
                <?= \app\widgets\ManageImages\FrontWidget::widget([
                    'model'    => $model,
                    'atributo' => 'cur_imagen',
                    'htmlOptions' => [
                        'style' => 'max-width: 100%; cursor: pointer;',
                        'loading' => 'lazy',
                    ],
                ]) ?>
            </div>

            <!-- Widget de selección de íconos -->
            <?= IconPickerWidget::widget(['model' => $model, 'attribute' => 'cur_icono']) ?>
        </div>

        <!-- Columna derecha -->
        <div class="col-md-8 border">
            <div style="padding-top: 10px; padding-bottom: 10px;">
            
                <?= $form->field($model, 'cur_estado')->dropDownList(['borrador' => 'Borrador', 'publicado' => 'Publicado'], ['prompt' => 'Seleccione estado']) ?>
                <?= $form->field($model, 'cur_titulo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'cur_descripcion')->textarea(['rows' => 6, 'id' => 'tinyMCE' , 'class' => 'tinymce']) ?>
                
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>