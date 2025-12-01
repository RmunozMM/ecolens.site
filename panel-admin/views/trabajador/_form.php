<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\helpers\LibreriaHelper;


/** @var yii\web\View $this */
/** @var app\models\Trabajador $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data']
]); ?>
<div class="trabajador-form">

    <div class="row">
        <div class="form-group btn_save">
            <?= Html::submitButton('Guardar cambios', ['class' => 'btn btn-success']) ?>
        </div>

        <!-- Columna izquierda para la imagen -->
        <div class="col-md-4">
            <?= $form->field($model, 'tra_foto_perfil')->fileInput([
                'class' => 'form-control img-view img-thumbnail mt-4',
            ]) ?>

            <label style="padding-top: 30px;">Imagen actual</label>
            <div class="border p-3 text-center">
                <?= \app\widgets\ManageImages\FrontWidget::widget([
                    'model'    => $model,
                    'atributo' => 'tra_foto_perfil',
                    'htmlOptions' => [
                        'style' => 'max-width: 100%; cursor: pointer;',
                        'loading' => 'lazy',
                    ],
                ]) ?>
            </div>

            <?= $form->field($model, 'tra_modalidad_contrato')
                    ->dropDownList(
                        ['Plazo Fijo' => 'Plazo Fijo', 'Indefinido' => 'Indefinido', 'A Demanda' => 'A Demanda'],
                        ['prompt' => 'Seleccione modalidad…', 'required' => true]
                    ) ?>

            <?= $form->field($model, 'tra_publicado')
                    ->radioList(['SI' => 'Sí', 'NO' => 'No'], ['class' => 'd-flex gap-3']) ?>

            <?= $form->field($model, 'tra_estado')
                    ->radioList(['Activo' => 'Activo', 'Inactivo' => 'Inactivo'], ['class' => 'd-flex gap-3']) ?>

            <?= $form->field($model, 'tra_puesto')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'tra_departamento')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'tra_fecha_contratacion')->input('date') ?>

            <?= $form->field($model, 'tra_salario')
                    ->input('number', ['step' => '0.01', 'min' => '0']) ?>

        </div>

        <!-- Columna derecha -->
        <div class="col-md-8 border">
            <div class="py-3">

                <?= $form->field($model, 'tra_nombre')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'tra_apellido')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'tra_cedula')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'tra_fecha_nacimiento')->input('date') ?>

                <?= $form->field($model, 'tra_genero')
                        ->dropDownList(
                            ['Masculino' => 'Masculino', 'Femenino' => 'Femenino', 'Otro' => 'Otro'],
                            ['prompt' => 'Seleccione género…']
                        ) ?>

                <?= $form->field($model, 'tra_email')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'tra_telefono')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'tra_direccion')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'tra_descripcion')->textarea(['rows' => 6, 'id' => 'tinyMCE' , 'class' => 'tinymce']) ?>

                <?= $form->field($model, 'tra_facebook')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'tra_instagram')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'tra_linkedin')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'tra_tiktok')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'tra_twitter')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'tra_whatsapp')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>

</div>
