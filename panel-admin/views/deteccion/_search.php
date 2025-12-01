<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\DeteccionSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="deteccion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'det_id')->textInput(['placeholder' => 'ID'])->label('ID') ?>

    <?= $form->field($model, 'det_confianza_router')->textInput(['placeholder' => 'Conf. Router'])->label('Confianza Router') ?>

    <?= $form->field($model, 'det_confianza_experto')->textInput(['placeholder' => 'Conf. Experto'])->label('Confianza Experto') ?>

    <?= $form->field($model, 'det_tax_id')->textInput(['placeholder' => 'Taxón'])->label('Taxonomía') ?>

    <?= $form->field($model, 'det_esp_id')->textInput(['placeholder' => 'Especie'])->label('Especie') ?>

    <?= $form->field($model, 'det_fuente')->dropDownList([
        '' => 'Todas',
        'web' => 'Web',
        'api' => 'API',
        'movil' => 'Móvil',
        'sistema' => 'Sistema',
    ], ['class' => 'form-control'])->label('Fuente') ?>

    <?= $form->field($model, 'det_estado')->dropDownList([
        '' => 'Todos',
        'pendiente' => 'Pendiente',
        'validada'  => 'Validada',
        'rechazada' => 'Rechazada',
    ], ['class' => 'form-control'])->label('Estado') ?>

    <?= $form->field($model, 'det_fecha')->input('date')->label('Fecha detección') ?>

    <div class="form-group ml-3">
        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Limpiar', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>