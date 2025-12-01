<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Testimonio $model */

$this->title = 'Actualizar Testimonio de: ' . $model->tes_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Testimonios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tes_nombre, 'url' => ['view', 'tes_id' => $model->tes_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="testimonio-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
