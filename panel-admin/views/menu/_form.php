<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\widgets\IconPicker\IconPickerWidget;

/** @var yii\web\View $this */
/** @var app\models\Menu $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="menu-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group btn_save mb-3">
        <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
    </div>

    <div class="row">
        <!-- Columna izquierda: inputs principales -->
        <div class="col-md-4">
            <?= $form->field($model, 'men_nombre')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'men_nivel')->dropDownList([
                'nivel_1' => 'Nivel 1',
                'nivel_2' => 'Nivel 2',
            ], ['prompt' => '', 'id' => 'menu_men_nivel']) ?>

            <div class="form-group" id="menPadreIdField">
                <?= $form->field($model, 'men_padre_id')->dropDownList(
                    ArrayHelper::map(\app\models\Menu::find()->where(['men_nivel' => 'nivel_1'])->all(), 'men_id', 'men_nombre'),
                    ['prompt' => 'Seleccionar Menú']
                )->label('Menú Nivel 1') ?>
            </div>

            <?= $form->field($model, 'men_url')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'men_etiqueta')->textInput(['maxlength' => true]) ?>

            <!-- Ícono del menú -->
            <?= IconPickerWidget::widget(['model' => $model, 'attribute' => 'men_icono']) ?>

            <?= $form->field($model, 'men_mostrar')->dropDownList(['Si' => 'Si', 'No' => 'No'], ['prompt' => '']) ?>
        </div>

        <!-- Columna derecha: detalles avanzados -->
        <div class="col-md-8 border">
            <div style="padding: 12px 0;">
                <?= $form->field($model, 'men_link_options')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'men_target')->dropDownList([
                    '_blank' => ' blank',
                    '_self' => ' self',
                    '_parent' => ' parent',
                    '_top' => ' top',
                ], ['prompt' => '']) ?>

                <?= $form->field($model, 'men_rol_id')->dropDownList(
                    ArrayHelper::map(\app\models\Rol::find()->all(), 'rol_id', 'rol_nombre'),
                    [
                        'prompt' => 'Seleccionar Rol',
                        'options' => [
                            '3' => ['selected' => true] // valor predeterminado
                        ]
                    ]
                )->label('Rol del Usuario') ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var menNivelField = document.getElementById("menu_men_nivel");
    var menPadreIdField = document.getElementById("menPadreIdField");
    var menUrlField = document.getElementById("menu-men_url");

    menNivelField.addEventListener("change", function() {
        var selectedValue = menNivelField.value;
        if (selectedValue === "nivel_2") {
            menPadreIdField.style.display = "block";
            menUrlField.required = true;
        } else {
            menPadreIdField.style.display = "none";
            menUrlField.required = false;
        }
    });

    // Inicialización
    if (menNivelField.value === "nivel_2") {
        menPadreIdField.style.display = "block";
        menUrlField.required = true;
    } else {
        menPadreIdField.style.display = "none";
        menUrlField.required = false;
    }
});
</script>