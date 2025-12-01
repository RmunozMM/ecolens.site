<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Newsletter $model */
/** @var yii\widgets\ActiveForm $form */

$zonaHoraria = new \DateTimeZone('America/Santiago');
$fechaActual = new \DateTime('now', $zonaHoraria);
$fechaFormateada = $fechaActual->format('Y-m-d H:i:s');

if ($model->isNewRecord) {
    $model->created_at = $fechaFormateada;
    $model->updated_at = $fechaFormateada;
} else {
    $model->updated_at = $fechaFormateada;
}
?>

<div class="newsletter-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'new_email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'new_estado')->dropDownList(['activo' => 'Activo', 'pendiente' => 'Pendiente'], ['prompt' => '']) ?>
    <?= $form->field($model, 'new_verificado')->dropDownList(['SI' => 'Sí', 'NO' => 'No'], ['prompt' => '']) ?>


    <div class="form-group">
        <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>