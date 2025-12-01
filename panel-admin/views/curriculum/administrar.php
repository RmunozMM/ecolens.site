<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Curriculum $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Administrar mi Curriculum';
?>
<div class="curriculum-form">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?= $form->field($model, 'cur_titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cur_subtitulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cur_casa_estudio')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'cur_resumen_profesional')->textarea(['rows' => 10, 'id' => 'tinyMCE', 'class' => 'tinymce' ,'style' => 'height: 600px;']) ?>


    <?= $form->field($model, 'cur_estilos')->textarea(['rows' => 10, 'id' => 'tinyMCE', 'style' => 'height: 600px;' , 'class' => 'tinymce']) ?>


    <?php 
        $zonaHoraria = new DateTimeZone('America/Santiago');
        $fechaActual = new DateTime('now', $zonaHoraria);
        $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');

        if($model->isNewRecord){
            $model->created_at = $fechaFormateada;
            $model->updated_at = $fechaFormateada;
        } else {
            $model->updated_at = $fechaFormateada;
        }
    ?>

    <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'updated_at')->hiddenInput()->label(false) ?>

    <?php ActiveForm::end(); ?>

</div>