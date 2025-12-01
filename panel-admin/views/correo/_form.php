<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Correo $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="correo-form">

    <?php $form = ActiveForm::begin(); ?>
    <table class="table">
        <div class="form-group btn_save">
            <?= Html::submitButton('Enviar Respuesta', ['class' => 'btn btn-success']) ?>
        </div>

        <tbody>
            <tr>
                <td><strong>Nombre:</strong></td>
                <td><?= $model->cor_nombre ?></td>
            </tr>
            <tr>
                <td><strong>Correo electrónico:</strong></td>
                <td><?= $model->cor_correo ?></td>
            </tr>
            <tr>
                <td><strong>Asunto:</strong></td>
                <td><?= $model->cor_asunto ?></td>
            </tr>
            <tr>
                <td><strong>Mensaje:</strong></td>
                <td><?= $model->cor_mensaje ?></td>
            </tr>
            <tr>
                <td><strong>Fecha de consulta:</strong></td>
                <td><?= $model->cor_fecha_consulta ?></td>
            </tr>
        </tbody>

        <?= $form->field($model, 'cor_respuesta')->textarea(['rows' => 6, 'id' => 'tinyMCE', 'class' => 'tinymce']) ?>

    </table>


    <?php ActiveForm::end(); ?>

</div>
