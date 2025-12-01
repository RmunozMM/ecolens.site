<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\DispositivoSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="dispositivo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'dis_id') ?>

    <?= $form->field($model, 'dis_tipo') ?>

    <?= $form->field($model, 'dis_sistema_operativo') ?>

    <?= $form->field($model, 'dis_navegador') ?>

    <?= $form->field($model, 'dis_user_agent') ?>

    <?php // echo $form->field($model, 'dis_ip_origen') ?>

    <?php // echo $form->field($model, 'dis_usuario_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
