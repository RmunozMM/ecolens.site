<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MenuSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="menu-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'men_id') ?>

    <?= $form->field($model, 'men_nombre') ?>

    <?= $form->field($model, 'men_url') ?>

    <?= $form->field($model, 'men_etiqueta') ?>

    <?= $form->field($model, 'men_mostrar') ?>

    <?php // echo $form->field($model, 'men_nivel') ?>

    <?php // echo $form->field($model, 'men_link_options') ?>

    <?php // echo $form->field($model, 'men_target') ?>

    <?php // echo $form->field($model, 'men_rol_id') ?>

    <?php // echo $form->field($model, 'men_padre_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
