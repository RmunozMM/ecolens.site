<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Recurso $model */

$this->title = 'Actualizar Recurso: ' . $model->rec_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Recursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->rec_titulo, 'url' => ['view', 'rec_id' => $model->rec_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="recurso-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
