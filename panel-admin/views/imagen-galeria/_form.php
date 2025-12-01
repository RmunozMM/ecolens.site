<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\LibreriaHelper;


?>

<div class="imagenes-galeria-form">
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']  // ¡Importante!
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
    </div>

    <!-- Campo oculto para la galería (gal_id) -->
    <?= $form->field($model, 'img_gal_id')->hiddenInput(['value' => Yii::$app->request->get('gal_id')])->label(false) ?>

    <!-- Campo para la descripción -->
    <?= $form->field($model, 'img_descripcion')->textInput(['maxlength' => true]) ?>

    <!-- Campo para el archivo -->
    <?= $form->field($model, 'img_ruta')->fileInput([
        'class' => 'form-control img-view img-thumbnail mt-4',
    ]) ?>

    <!-- Vista previa si ya existe una imagen -->
    <div>
        <?php if ($model->img_ruta): ?>
            <?= Html::img(LibreriaHelper::getRecursos(). "uploads/" . $model->img_ruta, ['width' => '30%']) ?>
        <?php endif; ?>
    </div>

    <?= $form->field($model, 'img_estado')->dropDownList([
        'publicado' => 'Publicado', 
        'borrador'  => 'Borrador',
    ], ['prompt' => '']) ?>

    <?php ActiveForm::end(); ?>
</div>