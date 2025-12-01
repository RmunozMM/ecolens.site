<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\GaleriaButtonWidget;

/** @var yii\web\View $this */
/** @var app\models\Dispositivo $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="dispositivo-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="form-group btn_save">
            <?= Html::submitButton(
                $model->isNewRecord ? 'Crear' : 'Actualizar',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
            ) ?>
            <?= GaleriaButtonWidget::widget() ?>
        </div>

        <!-- Columna 1 -->
        <div class="col-md-6">
            <?= $form->field($model, 'dis_tipo')->dropDownList([
                'desktop' => 'Desktop',
                'mobile' => 'Mobile',
                'tablet' => 'Tablet',
                'camera' => 'Camera',
                'api' => 'API',
            ], ['prompt' => 'Seleccione tipo']) ?>

            <?= $form->field($model, 'dis_sistema_operativo')->dropDownList([
                'Windows' => 'Windows',
                'macOS' => 'MacOS',
                'Linux' => 'Linux',
                'Android' => 'Android',
                'iOS' => 'iOS',
                'Otro' => 'Otro',
            ], ['prompt' => 'Seleccione sistema']) ?>

            <?= $form->field($model, 'dis_navegador')->dropDownList([
                'Chrome' => 'Chrome',
                'Safari' => 'Safari',
                'Firefox' => 'Firefox',
                'Edge' => 'Edge',
                'Otro' => 'Otro',
            ], ['prompt' => 'Seleccione navegador']) ?>
        </div>

        <!-- Columna 2 -->
        <div class="col-md-6">
            <?= $form->field($model, 'dis_user_agent')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'dis_ip_origen')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'dis_usuario_id')->textInput() ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>