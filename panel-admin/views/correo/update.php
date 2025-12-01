<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Correo $model */

$this->title = 'Responder Correo Número: ' . $model->cor_id . ' Enviado por ' .  $model->cor_nombre . ' enviado el ' . $model->cor_fecha_consulta;
$this->params['breadcrumbs'][] = ['label' => 'Correos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cor_id, 'url' => ['view', 'cor_id' => $model->cor_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="correo-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
