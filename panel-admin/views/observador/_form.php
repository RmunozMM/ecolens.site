<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\GaleriaButtonWidget;
use app\widgets\ManageImages\FrontWidget;

/** @var yii\web\View $this */
/** @var app\models\Observador $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="observador-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="form-group btn_save">
            <?= Html::submitButton(
                $model->isNewRecord ? 'Crear' : 'Actualizar',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
            ) ?>
            <?= GaleriaButtonWidget::widget() ?>
        </div>

        <!-- 📸 Columna izquierda: Foto del observador -->
        <div class="col-md-4">
            <?= $form->field($model, 'obs_foto')->fileInput([
                'class' => 'form-control img-view img-thumbnail mt-4',
            ]) ?>

            <label style="padding-top: 30px;">Foto actual</label>
            <div class="border p-3 text-center">
                <?= FrontWidget::widget([
                    'model' => $model,
                    'atributo' => 'obs_foto',
                    'htmlOptions' => [
                        'style' => 'max-width: 100%; cursor: pointer;',
                        'loading' => 'lazy',
                    ],
                ]) ?>
            </div>
        </div>

        <!-- 🧾 Columna derecha: Información -->
        <div class="col-md-8 border">
            <div style="padding-top: 10px; padding-bottom: 10px;">

                <?= $form->field($model, 'obs_nombre')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'obs_email')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'obs_institucion')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'obs_experiencia')->dropDownList([
                    'principiante' => 'Principiante',
                    'aficionado' => 'Aficionado',
                    'experto' => 'Experto',
                    'institucional' => 'Institucional',
                ], ['prompt' => 'Selecciona nivel de experiencia']) ?>

                <?= $form->field($model, 'obs_pais')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'obs_ciudad')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'obs_estado')->dropDownList([
                    'activo' => 'Activo',
                    'inactivo' => 'Inactivo',
                    'pendiente' => 'Pendiente',
                ], ['prompt' => 'Seleccione estado']) ?>

                <?= $form->field($model, 'obs_fecha_registro')->textInput([
                    'type' => 'datetime-local'
                ]) ?>

            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>