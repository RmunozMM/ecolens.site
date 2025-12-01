<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Asunto $model */

$this->title = 'Actualizar Asunto de formulario de contacto: ' . $model->asu_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Asuntos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->asu_nombre, 'url' => ['view', 'asu_id' => $model->asu_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="asuntos-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
