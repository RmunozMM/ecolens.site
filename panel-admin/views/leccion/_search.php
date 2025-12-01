<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\LeccionSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="leccion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'lec_id') ?>

    <?= $form->field($model, 'lec_titulo') ?>

    <?= $form->field($model, 'lec_contenido') ?>

    <?= $form->field($model, 'lec_tipo') ?>

    <?= $form->field($model, 'lec_orden') ?>

    <?php // echo $form->field($model, 'lec_estado') ?>

    <?php // echo $form->field($model, 'lec_slug') ?>

    <?php // echo $form->field($model, 'lec_usu_id') ?>

    <?php // echo $form->field($model, 'lec_mod_id') ?>

    <?php // echo $form->field($model, 'lec_fecha_creacion') ?>

    <?php // echo $form->field($model, 'lec_fecha_actualizacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
