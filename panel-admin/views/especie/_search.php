<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\EspecieSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="especie-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'esp_id') ?>

    <?= $form->field($model, 'esp_nombre_cientifico') ?>

    <?= $form->field($model, 'esp_nombre_comun') ?>

    <?= $form->field($model, 'esp_tax_id') ?>

    <?= $form->field($model, 'esp_descripcion') ?>

    <?php // echo $form->field($model, 'esp_imagen') ?>

    <?php // echo $form->field($model, 'esp_estado') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
