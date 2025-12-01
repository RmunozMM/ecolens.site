<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\RecursoSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="recurso-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'rec_id') ?>

    <?= $form->field($model, 'rec_tipo') ?>

    <?= $form->field($model, 'rec_url') ?>

    <?= $form->field($model, 'rec_descripcion') ?>

    <?= $form->field($model, 'rec_estado') ?>

    <?php // echo $form->field($model, 'rec_usu_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
