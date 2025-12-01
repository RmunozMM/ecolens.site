<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ModuloSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="modulo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'mod_id') ?>

    <?= $form->field($model, 'mod_titulo') ?>

    <?= $form->field($model, 'mod_orden') ?>

    <?= $form->field($model, 'mod_estado') ?>

    <?= $form->field($model, 'mod_slug') ?>

    <?php // echo $form->field($model, 'mod_usu_id') ?>

    <?php // echo $form->field($model, 'mod_cur_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
