<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Users $user */
/** @var yii\base\DynamicModel $formModel */

$this->title = "Actualizar Correo Electrónico";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="update-email container">
    <h2><?= Html::encode($this->title) ?></h2>
    <p>Correo electrónico actual: <strong><?= Html::encode($user->usu_email) ?></strong></p>

    <?php $form = ActiveForm::begin([
         'method' => 'post',
         'id' => 'form-update-email',
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($formModel, 'new_email')
                ->textInput(['placeholder' => 'Nuevo correo'])
                ->label('Nuevo correo') ?>
            <?= $form->field($formModel, 'confirm_email')
                ->textInput(['placeholder' => 'Confirmar nuevo correo'])
                ->label('Confirmar correo') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($formModel, 'current_password')
                ->passwordInput(['placeholder' => 'Contraseña actual'])
                ->label('Contraseña actual') ?>
        </div>
    </div>

    <div class="form-group">
         <?= Html::submitButton('Actualizar correo', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>