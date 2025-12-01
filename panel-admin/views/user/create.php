<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Users $model */

$this->title = 'Crear Usuario';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Crear Usuario';

?>
<div class="users-create">

    <h2>Crear Usuarios</h2>

    <?= $this->render('_form', [
        'model' => $model,
        'msg' => $msg,
    ]) ?>

</div>
