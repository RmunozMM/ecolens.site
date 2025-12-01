<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\HabilidadSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="habilidad-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'hab_id') ?>

    <?= $form->field($model, 'hab_nombre') ?>

    <?= $form->field($model, 'hab_nivel') ?>

    <?= $form->field($model, 'hab_publicada') ?>

    <?= $form->field($model, 'hab_usu_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
