<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Roles $model */

$this->title = 'Actualizar Rol: ' . $model->rol_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->rol_nombre, 'url' => ['view', 'rol_id' => $model->rol_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="roles-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
