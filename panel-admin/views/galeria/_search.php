<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\GaleriaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="galerias-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'gal_id') ?>

    <?= $form->field($model, 'gal_tipo_registro') ?>

    <?= $form->field($model, 'gal_id_registro') ?>

    <?= $form->field($model, 'gal_descripcion') ?>

    <?= $form->field($model, 'gal_estado') ?>

    <?php // echo $form->field($model, 'gal_titulo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
