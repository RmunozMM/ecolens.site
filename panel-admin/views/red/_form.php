<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Redes $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="redes-form">

<?php $form = ActiveForm::begin(); ?>

<?php if (Yii::$app->user->identity->usu_rol_id == 1 || Yii::$app->user->identity->usu_rol_id == 2): ?>
    <?= $form->field($model, 'red_enlace')->textInput(['maxlength' => true]) ?>
<?php else: ?>
    <?= $form->field($model, 'red_enlace')->hiddenInput(['value' => $model->red_nombre])->label(false) ?>
<?php endif; ?>


<?= $form->field($model, 'red_perfil')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'red_publicada')->dropDownList([ 'SI' => 'SI', 'NO' => 'NO', ], ['prompt' => 'Seleccione']) ?>

<div class="form-group">
    <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

</div>
