<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\LibreriaHelper;





/** @var yii\web\View $this */
/** @var app\models\Perfil $model */
/** @var yii\widgets\ActiveForm $form */


$this->title = 'Actualizar Mi Perfil';

?>


<div class="perfil-form">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <!-- Botones de guardar y otras acciones arriba, igual que en Artículos -->
        <div class="form-group btn_save">
            <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
            <!-- Si quieres un botón extra, como "Crear Galería", agrégalo aquí -->
            <!-- <?= Html::a('Crear Galería', ['galeria/create'], ['class' => 'btn btn-primary']) ?> -->
        </div>

        <!-- Columna izquierda para la imagen -->
        <div class="col-md-4">
            <?= $form->field($model, 'per_imagen')->fileInput([
                'class' => 'form-control img-view img-thumbnail mt-4',
            ]) ?>

            <label style="padding-top: 30px;">Imagen actual</label>
            <div class="border p-3 text-center">
                <?= \app\widgets\ManageImages\FrontWidget::widget([
                    'model'    => $model,
                    'atributo' => 'per_imagen',
                    'htmlOptions' => [
                        'style' => 'max-width: 100%; cursor: pointer;',
                        'loading' => 'lazy',
                    ],
                ]) ?>
            </div>
        </div>

        <!-- 📌 Columna derecha para los demás campos (col-md-8), con borde -->
        <div class="col-md-8 border">
            <div style="padding-top:10px; padding-bottom:10px;">
                
                <!-- Replicamos los campos de texto igual que en Artículos -->
                <?= $form->field($model, 'per_tipo')->dropDownList(
                    ['persona' => 'Persona', 'empresa' => 'Empresa'],
                    ['prompt' => 'Selecciona el tipo']
                ) ?>
                <?= $form->field($model, 'per_nombre')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'per_fecha_nacimiento')->textInput() ?>
                <?= $form->field($model, 'per_lugar_nacimiento_fundacion')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'per_ubicacion')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'per_nacionalidad')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'per_correo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'per_telefono')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'per_direccion')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'per_linkedin')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'per_sitio_web')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'per_sector')->textInput(['maxlength' => true]) ?>
                
                <!-- Ejemplo de un textarea que quieras manejar con TinyMCE -->
                <?= $form->field($model, 'per_idiomas')->textarea([
                    'rows' => 6,
                    'id' => 'tinyMCE', // si lo vas a usar con TinyMCE
                    'class' => 'tinymce'
                ]) ?>

                <!-- Campos de fechas, o hidden fields, si lo deseas -->
                <?= $form->field($model, 'updated_at')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>

            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>
</div>

<!-- Estilos que unifican la apariencia con Artículos -->
<style>
    .perfil-form .btn_save {
        margin-bottom: 20px;
    }
    .img-view {
        margin-bottom: 10px;
    }
</style>