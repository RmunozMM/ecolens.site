<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TrabajadorSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="trabajador-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'tra_id') ?>

    <?= $form->field($model, 'tra_nombre') ?>

    <?= $form->field($model, 'tra_apellido') ?>

    <?= $form->field($model, 'tra_cedula') ?>

    <?= $form->field($model, 'tra_fecha_nacimiento') ?>

    <?php // echo $form->field($model, 'tra_genero') ?>

    <?php // echo $form->field($model, 'tra_puesto') ?>

    <?php // echo $form->field($model, 'tra_departamento') ?>

    <?php // echo $form->field($model, 'tra_fecha_contratacion') ?>

    <?php // echo $form->field($model, 'tra_salario') ?>

    <?php // echo $form->field($model, 'tra_email') ?>

    <?php // echo $form->field($model, 'tra_telefono') ?>

    <?php // echo $form->field($model, 'tra_direccion') ?>

    <?php // echo $form->field($model, 'tra_foto_perfil') ?>

    <?php // echo $form->field($model, 'tra_descripcion') ?>

    <?php // echo $form->field($model, 'tra_facebook') ?>

    <?php // echo $form->field($model, 'tra_instagram') ?>

    <?php // echo $form->field($model, 'tra_linkedin') ?>

    <?php // echo $form->field($model, 'tra_tiktok') ?>

    <?php // echo $form->field($model, 'tra_twitter') ?>

    <?php // echo $form->field($model, 'tra_whatsapp') ?>

    <?php // echo $form->field($model, 'tra_modalidad_contrato') ?>

    <?php // echo $form->field($model, 'tra_publicado') ?>

    <?php // echo $form->field($model, 'tra_estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
