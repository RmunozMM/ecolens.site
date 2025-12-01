<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\LayoutSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="layouts-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'lay_id') ?>

    <?= $form->field($model, 'lay_nombre') ?>

    <?= $form->field($model, 'lay_ruta_imagenes') ?>

    <?= $form->field($model, 'lay_estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
