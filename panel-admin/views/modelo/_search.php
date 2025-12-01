<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ModeloSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="modelo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'mod_id') ?>

    <?= $form->field($model, 'mod_nombre') ?>

    <?= $form->field($model, 'mod_version') ?>

    <?= $form->field($model, 'mod_archivo') ?>

    <?= $form->field($model, 'mod_dataset') ?>

    <?php // echo $form->field($model, 'mod_precision_val') ?>

    <?php // echo $form->field($model, 'mod_fecha_entrenamiento') ?>

    <?php // echo $form->field($model, 'mod_estado') ?>

    <?php // echo $form->field($model, 'mod_notas') ?>

    <?php // echo $form->field($model, 'mod_tipo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
