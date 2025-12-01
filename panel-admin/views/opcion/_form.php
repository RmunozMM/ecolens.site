<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\CategoriaOpcion;
use app\models\Rol;

/** @var yii\web\View $this */
/** @var app\models\Opcion $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="opcion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'opc_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'opc_tipo')->dropDownList([
        'string' => 'String',
        'int' => 'Int',
        'bool' => 'Bool',
        'float' => 'Float',
        'json' => 'Json',
        'enum' => 'Enum',
        'color' => 'Color',
    ], ['prompt' => '', 'id' => 'opc-tipo-select']) ?>

    <div id="opc-valor-input">
        <?php
        switch ($model->opc_tipo) {
            case 'int':
                echo $form->field($model, 'opc_valor')->input('number');
                break;
            case 'bool':
                echo $form->field($model, 'opc_valor')->dropDownList(['yes' => 'Sí', 'no' => 'No'], ['prompt' => 'Seleccione...']);
                break;
            case 'color':
                echo $form->field($model, 'opc_valor')->input('color');
                break;
            case 'json':
                echo $form->field($model, 'opc_valor')->textarea(['rows' => 4, 'placeholder' => 'Pegue aquí JSON válido...']);
                break;
            default:
                echo $form->field($model, 'opc_valor')->textInput();
                break;
        }
        ?>
    </div>

    <?= $form->field($model, 'opc_cat_id')->dropDownList(
        ArrayHelper::map(CategoriaOpcion::find()->orderBy('cat_nombre')->all(), 'cat_id', 'cat_nombre'),
        ['prompt' => 'Seleccione una categoría...']
    ) ?>

    <?= $form->field($model, 'opc_rol_id')->dropDownList(
        ArrayHelper::map(Rol::find()->orderBy('rol_nombre')->all(), 'rol_id', 'rol_nombre'),
        ['prompt' => 'Seleccione un rol mínimo...']
    ) ?>

    <?= $form->field($model, 'opc_descripcion')->textInput(['maxlength' => true]) ?>

    <?php /* Campos de auditoría, usualmente automáticos, los puedes ocultar si no necesitas editarlos manualmente:
    <?= $form->field($model, 'created_at')->textInput(['readonly' => true]) ?>
    <?= $form->field($model, 'updated_at')->textInput(['readonly' => true]) ?>
    <?= $form->field($model, 'created_by')->dropDownList(
        ArrayHelper::map(app\models\Users::find()->all(), 'usu_id', 'usu_username'),
        ['prompt' => 'Seleccione usuario...', 'readonly' => true]
    ) ?>
    <?= $form->field($model, 'updated_by')->dropDownList(
        ArrayHelper::map(app\models\Users::find()->all(), 'usu_id', 'usu_username'),
        ['prompt' => 'Seleccione usuario...', 'readonly' => true]
    ) ?>
    */ ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
// Lógica para actualizar dinámicamente el input de valor según tipo seleccionado
$this->registerJs(<<<JS
    $('#opc-tipo-select').on('change', function() {
        var tipo = $(this).val();
        var inputHtml = '';
        switch (tipo) {
            case 'int':
                inputHtml = '<input type="number" id="opcion-opc_valor" class="form-control" name="Opcion[opc_valor]">';
                break;
            case 'bool':
                inputHtml = '<select id="opcion-opc_valor" class="form-control" name="Opcion[opc_valor]">'
                          + '<option value="">Seleccione...</option>'
                          + '<option value="yes">Sí</option>'
                          + '<option value="no">No</option>'
                          + '</select>';
                break;
            case 'color':
                inputHtml = '<input type="color" id="opcion-opc_valor" class="form-control form-control-color" name="Opcion[opc_valor]">';
                break;
            case 'json':
                inputHtml = '<textarea id="opcion-opc_valor" class="form-control" rows="4" name="Opcion[opc_valor]" placeholder="Pegue aquí JSON válido..."></textarea>';
                break;
            default:
                inputHtml = '<input type="text" id="opcion-opc_valor" class="form-control" name="Opcion[opc_valor]">';
        }
        $('#opc-valor-input').html('<div class="form-group field-opcion-opc_valor required"><label class="control-label" for="opcion-opc_valor">Opc Valor</label>' + inputHtml + '</div>');
    });
JS);
?>