<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\FormRegister;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

use app\helpers\LibreriaHelper;

?>
    <div class="users-create">
        <?php if($msg != null){?>
            <div class="alert alert-success" role="alert">
                <?php echo $msg;?>
            </div>
        <?php } ?>
        <?php if(Yii::$app->user->identity->usu_rol_id == 1){ ?>
            <p>Recuerda que por defecto la contraseña es <b>año_username</b>. En minúsculas</p>
        <?php } ?>
        <?php $form = ActiveForm::begin([
            'method' => 'post',
        ]);
        ?>
        <div class="form-group">
        <?= $form->field($model, "usu_username")->input("text")->label('Usuario') ?>   
        </div>

        <div class="form-group">
        <?= $form->field($model, "usu_email")->input("email")->label('Correo electrónico') ?>   
        </div>

        <div class="form-group">
            <?= $form->field($model, 'usu_rol_id')->dropDownList(
                \yii\helpers\ArrayHelper::map(\app\models\Rol::find()->all(), 'rol_id', 'rol_nombre'),
                ['prompt' => 'Seleccionar Rol']
            )->label('Rol del Usuario') ?>
        </div>
        
        <div class="form-group">
            <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
        </div>


        <?php $form->end() ?>
    </div>
