<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\FormacionSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="formacion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'for_id') ?>

    <?= $form->field($model, 'for_institucion') ?>

    <?= $form->field($model, 'for_grado_titulo') ?>

    <?= $form->field($model, 'for_fecha_inicio') ?>

    <?= $form->field($model, 'for_fecha_fin') ?>

    <?php // echo $form->field($model, 'for_logros_principales') ?>

    <?php echo $form->field($model, 'for_tipo_logro') ?>

    <?php echo $form->field($model, 'for_categoria') ?>

    <?php  echo $form->field($model, 'for_publicada') ?>

    <?php // echo $form->field($model, 'for_codigo_validacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
