<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\GaleriaButtonWidget;
use app\widgets\ManageImages\FrontWidget;

/** @var yii\web\View $this */
/** @var app\models\Taxonomia $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="taxonomia-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="form-group btn_save">
            <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
            <?= GaleriaButtonWidget::widget() ?>
        </div>

        <!-- 📸 Columna izquierda: Imagen -->
        <div class="col-md-4">
            <?= $form->field($model, 'tax_imagen')->fileInput([
                'class' => 'form-control img-view img-thumbnail mt-4',
            ]) ?>

            <label style="padding-top: 30px;">Imagen actual</label>
            <div class="border p-3 text-center">
                <?= FrontWidget::widget([
                    'model' => $model,
                    'atributo' => 'tax_imagen',
                    'htmlOptions' => [
                        'style' => 'max-width: 100%; cursor: pointer;',
                        'loading' => 'lazy',
                    ],
                ]) ?>
            </div>
        </div>

        <!-- 🧾 Columna derecha: Contenido -->
        <div class="col-md-8 border">
            <div style="padding-top: 10px; padding-bottom: 10px;">

                <?= $form->field($model, 'tax_nombre')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'tax_nombre_comun')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'tax_descripcion')->textarea([
                    'rows' => 6,
                    'id' => 'tinyMCE',
                    'class' => 'tinymce'
                ]) ?>

                <?= $form->field($model, 'tax_estado')->dropDownList([
                    'activo' => 'Activo',
                    'inactivo' => 'Inactivo'
                ], ['prompt' => 'Selecciona estado']) ?>

            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>