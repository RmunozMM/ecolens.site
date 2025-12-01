<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CorreoSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="correo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cor_id') ?>

    <?= $form->field($model, 'cor_nombre') ?>

    <?= $form->field($model, 'cor_correo') ?>

    <?= $form->field($model, 'cor_asunto') ?>

    <?= $form->field($model, 'cor_mensaje') ?>

    <?php // echo $form->field($model, 'cor_fecha_consulta') ?>

    <?php // echo $form->field($model, 'cor_fecha_respuesta') ?>

    <?php // echo $form->field($model, 'cor_estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
