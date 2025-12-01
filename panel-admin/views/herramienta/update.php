<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Herramientas $model */

$this->title = 'Actualizar Herramientas: ' . $model->her_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Herramientas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->her_nombre, 'url' => ['view', 'her_id' => $model->her_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="herramienta-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
