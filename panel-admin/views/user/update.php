<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Users $model */

$this->title = 'Actualizar Usuario: ' . $model->usu_username;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->usu_username, 'url' => ['view', 'usu_id' => $model->usu_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="users-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'msg' => $msg,
    ]) ?>

</div>