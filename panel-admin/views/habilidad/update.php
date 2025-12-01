<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Habilidades $model */

$this->title = 'Actualizar Habilidades: ' . $model->hab_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Habilidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->hab_nombre, 'url' => ['view', 'hab_id' => $model->hab_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="habilidad-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
