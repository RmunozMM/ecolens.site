<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Habilidad $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="habilidad-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="form-group">
        <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success btn_save']) ?>
    </div>

    <?= $form->field($model, 'hab_nombre')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <label for="hab-nivel">Nivel</label>
        <div style="display: flex; align-items: center;">
            <!-- Slider -->
            <input type="range" min="0" max="100" step="20" id="hab-nivel" name="Habilidad[hab_nivel]" value="<?= isset($model->hab_nivel) ? $model->hab_nivel : 50 ?>" class="slider-habilidad" style="width: 80%; margin-right: 10px;">
            <!-- Valor del slider -->
            <span id="valor-nivel" style="min-width: 50px; text-align: right;"><?= isset($model->hab_nivel) ? $model->hab_nivel : 50 ?>%</span>
        </div>
    </div>

    <?= $form->field($model, 'hab_publicada')->dropDownList(['SI' => 'SI', 'NO' => 'NO'], ['prompt' => 'Seleccione estado']) ?>

    <?= $form->field($model, 'hab_usu_id')->hiddenInput(['value' => Yii::$app->user->identity->usu_id])->label(false) ?>

    <?php ActiveForm::end(); ?>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var slider = document.getElementById('hab-nivel');
        var spanValue = document.getElementById('valor-nivel');
        spanValue.textContent = slider.value + '%';
        slider.addEventListener('input', function() {
            spanValue.textContent = slider.value + '%';
        });
    });
</script>