<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\OpcionSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="opcion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'opc_id') ?>

    <?= $form->field($model, 'opc_nombre') ?>

    <?= $form->field($model, 'opc_valor') ?>

    <?= $form->field($model, 'opc_tipo') ?>

    <?= $form->field($model, 'opc_cat_id') ?>

    <?php // echo $form->field($model, 'opc_rol_id') ?>

    <?php // echo $form->field($model, 'opc_descripcion') ?>

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
