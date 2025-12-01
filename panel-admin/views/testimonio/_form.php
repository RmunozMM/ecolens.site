<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\LibreriaHelper;



/** @var yii\web\View $this */
/** @var app\models\Testimonio $model */
/** @var yii\widgets\ActiveForm $form */

?>
<div class="testimonio-form">
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->errorSummary($model) ?>

    <div class="row">
        <!-- Botón guardar -->
        <div class="form-group btn_save">
            <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
        </div>

        <!-- Columna izquierda para la imagen -->
        <div class="col-md-4">
            <?= $form->field($model, 'tes_imagen')->fileInput([
                'class' => 'form-control img-view img-thumbnail mt-4',
            ]) ?>

            <label>Imagen actual</label>
            <div class="border p-3 text-center mb-3">

                <?= \app\widgets\ManageImages\FrontWidget::widget([
                    'model'       => $model,
                    'atributo'    => 'tes_imagen',
                    'htmlOptions' => [
                        'style'   => 'max-width: 100%; cursor: pointer;',
                        'loading' => 'lazy',
                    ],
                ]) ?>
            </div>
        </div>

        <!-- 📝 Columna derecha para datos -->
        <div class="col-md-8 border">
            <div style="padding-top:10px;padding-bottom:10px;">
                
                <?= $form->field($model, 'tes_nombre')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'tes_cargo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'tes_empresa')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'tes_testimonio')->textarea(['rows' => 10, 'id' => 'tinyMCE', 'style' => 'height: 600px;' , 'class' => 'tinymce']) ?>

                <?= $form->field($model, 'tes_estado')->dropDownList([
                    'borrador' => 'Borrador',
                    'publicado' => 'Publicado',
                ], [
                    'prompt' => 'Seleccionar estado',
                    'options' => [
                        'borrador' => ['Selected' => $model->tes_estado === null],
                    ],
                ]) ?>

            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
