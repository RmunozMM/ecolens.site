<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PaginaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pagina-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pag_id') ?>

    <?= $form->field($model, 'pag_titulo') ?>

    <?= $form->field($model, 'pag_contenido') ?>

    <?= $form->field($model, 'pag_slug') ?>

    <?= $form->field($model, 'pag_estado') ?>

    <?php // echo $form->field($model, 'pag_fecha_creacion') ?>

    <?php // echo $form->field($model, 'pag_fecha_actualizacion') ?>

    <?php // echo $form->field($model, 'pag_autor_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
