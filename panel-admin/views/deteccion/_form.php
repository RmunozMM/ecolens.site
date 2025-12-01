<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\GaleriaButtonWidget;

/** @var yii\web\View $this */
/** @var app\models\Deteccion $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="deteccion-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="form-group btn_save">
            <?= Html::submitButton(
                $model->isNewRecord ? 'Crear' : 'Actualizar',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
            ) ?>
            <?= GaleriaButtonWidget::widget() ?>
        </div>

        <!-- 🌍 Columna izquierda: Localización -->
        <div class="col-md-4">
            <?= $form->field($model, 'det_latitud')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'det_longitud')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'det_ubicacion_textual')->textInput(['maxlength' => true]) ?>
        </div>

        <!-- 🧪 Columna derecha: Datos técnicos -->
        <div class="col-md-8 border">
            <div style="padding-top: 10px; padding-bottom: 10px;">

                <?= $form->field($model, 'det_imagen')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'det_tipo_archivo')->dropDownList([
                    'jpg' => 'JPG',
                    'jpeg' => 'JPEG',
                    'png' => 'PNG',
                    'heic' => 'HEIC',
                    'webp' => 'WEBP',
                    'otro' => 'Otro'
                ], ['prompt' => 'Seleccione tipo']) ?>

                <?= $form->field($model, 'det_confianza')->textInput([
                    'type' => 'number',
                    'step' => '0.0001'
                ]) ?>

                <?= $form->field($model, 'det_modelo_id')->textInput() ?>
                <?= $form->field($model, 'det_tax_id')->textInput() ?>
                <?= $form->field($model, 'det_esp_id')->textInput() ?>
                <?= $form->field($model, 'det_dispositivo_id')->textInput() ?>
                <?= $form->field($model, 'det_obs_id')->textInput() ?>

                <?= $form->field($model, 'det_fuente')->dropDownList([
                    'web' => 'Web',
                    'api' => 'API',
                    'móvil' => 'Móvil',
                    'sistema' => 'Sistema'
                ], ['prompt' => 'Seleccione fuente']) ?>

                <?= $form->field($model, 'det_fecha')->textInput([
                    'type' => 'datetime-local'
                ]) ?>

            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>