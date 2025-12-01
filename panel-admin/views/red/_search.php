<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\RedSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="redes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'red_id') ?>

    <?= $form->field($model, 'red_nombre') ?>

    <?= $form->field($model, 'red_enlace') ?>

    <?= $form->field($model, 'red_perfil') ?>

    <?= $form->field($model, 'red_publicada') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
