<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\HerramientaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="herramienta-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'her_id') ?>

    <?= $form->field($model, 'her_nombre') ?>

    <?= $form->field($model, 'her_nivel') ?>

    <?= $form->field($model, 'her_publicada') ?>

    <?= $form->field($model, 'her_usu_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
