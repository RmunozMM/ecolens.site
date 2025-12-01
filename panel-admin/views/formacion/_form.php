<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Formacion $model */
/** @var yii\widgets\ActiveForm $form */

use app\helpers\LibreriaHelper;

?>

<div class="formacion-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="form-group btn_save">
        <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
    </div>

    <div class="row">
        <!-- Columna izquierda para el pdf -->
        <div class="col-md-4 ">
            <?= $form->field($model, 'for_mostrar_certificado')->dropDownList([ 'SI' => 'Sí', 'NO' => 'No' ], ['prompt' => 'Seleccione']) ?>    
            
            <?= $form->field($model, 'for_certificado')->fileInput([
                'class' => 'form-control img-view img-thumbnail mt-4', // Agrega margen superior para el input file
            ]) ?>


            <?php if (!empty($model->for_certificado)): ?>
                <div class="mt-3">
                    <iframe src="<?= LibreriaHelper::getRecursos(). '/uploads/' . $model->for_certificado ?>" width="100%" height="400px"></iframe>
                </div>
            <?php endif; ?>
        </div>

        <!-- Columna derecha -->
        <div class="col-md-8 border">
            <div style="padding-top: 10px; padding-bottom: 10px;">

                <?= $form->field($model, 'for_institucion')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'for_grado_titulo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'for_fecha_inicio')->textInput(['class' => 'form-control', 'id' => 'datepicker']) ?>
                <?= $form->field($model, 'for_fecha_fin')->textInput(['class' => 'form-control', 'id' => 'datepicker']) ?>
                <?= $form->field($model, 'for_logros_principales')->textarea(['rows' => 6,'id' => 'tinyMCE', 'class' => 'tinymce']) ?>
                <?= $form->field($model, 'for_tipo_logro')->dropDownList(['Enseñanza' => 'Enseñanza', 'Licenciatura' => 'Licenciatura', 'Maestría' => 'Maestría', 'Doctorado' => 'Doctorado', 'Diplomado' => 'Diplomado', 'Certificación' => 'Certificación', 'Curso' => 'Curso', ], ['prompt' => 'Seleccione']) ?>
                <?= $form->field($model, 'for_categoria')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'for_publicada')->dropDownList([ 'SI' => 'SI', 'NO' => 'NO', ], ['prompt' => 'Seleccione']) ?>
                <?= $form->field($model, 'for_codigo_validacion')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'for_usu_id')->hiddenInput([ 'value'=>Yii::$app->user->identity->usu_id])->label(false) ?>
            </div>
        </div>

    </div>
    <?php ActiveForm::end(); ?>

</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    // Obtén los elementos relevantes
    var tipoLogroField = $('#formacion-for_tipo_logro');
    var categoriaField = $('#formacion-for_categoria');

    // Define una función para actualizar for_categoria en función de for_tipo_logro
    function actualizarCategoria() {
        var tipoLogro = tipoLogroField.val();
        var categoria = '';

        // Aplica tus reglas para determinar la categoría
        if (tipoLogro === 'Certificación') {
            categoria = 'Certificación';
        } else if (tipoLogro === 'Curso') {
            categoria = 'Curso';
        } else {
            categoria = 'Formación'; // Otra categoría por defecto
        }

        // Actualiza el valor de for_categoria
        categoriaField.val(categoria);
    }

    // Escucha cambios en for_tipo_logro y actualiza for_categoria
    tipoLogroField.change(function () {
        actualizarCategoria();
    });

    // Llama a actualizarCategoria una vez para configurar el valor inicial
    actualizarCategoria();
});
</script>