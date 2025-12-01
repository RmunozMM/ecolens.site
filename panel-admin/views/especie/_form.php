<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\GaleriaButtonWidget;
use app\widgets\ManageImages\FrontWidget;

/** @var yii\web\View $this */
/** @var app\models\Especie $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="especie-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="form-group btn_save">
            <?= Html::submitButton($model->isNewRecord ? 'Crear Especie' : 'Guardar Cambios', ['class' => 'btn btn-success']) ?>
            <?= GaleriaButtonWidget::widget() ?>
        </div>

        <!-- Columna izquierda (imagen) -->
        <div class="col-md-4">
            <?= $form->field($model, 'esp_imagen')->fileInput([
                'class' => 'form-control img-view img-thumbnail mt-4',
            ]) ?>

            <label style="padding-top: 30px;">Imagen actual</label>
            <div class="border p-3 text-center">
                <?= FrontWidget::widget([
                    'model' => $model,
                    'atributo' => 'esp_imagen',
                    'htmlOptions' => [
                        'style' => 'max-width: 100%; cursor: pointer;',
                        'loading' => 'lazy',
                    ],
                ]) ?>
            </div>
        </div>

        <!-- Columna derecha (datos) -->
        <div class="col-md-8 border">
            <div style="padding-top: 10px; padding-bottom: 10px;">

                <?= $form->field($model, 'esp_nombre_cientifico')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'esp_nombre_comun')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'esp_tax_id')->dropDownList(
                    \yii\helpers\ArrayHelper::map(\app\models\Taxonomia::find()->all(), 'tax_id', 'tax_nombre'),
                    ['prompt' => 'Selecciona una taxonomía']
                ) ?>

                <?= $form->field($model, 'esp_estado')->dropDownList([
                    'activo' => 'Activo',
                    'inactivo' => 'Inactivo',
                ], ['prompt' => '']) ?>

                <?= $form->field($model, 'esp_descripcion')->textarea([
                    'rows' => 6,
                    'id' => 'tinyMCE',
                    'class' => 'tinymce'
                ]) ?>

            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>