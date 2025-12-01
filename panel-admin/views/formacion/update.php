<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Formacion $model */

$this->title = 'Actualizar Formación: ' . $model->for_grado_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Formación Académica', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->for_grado_titulo, 'url' => ['view', 'for_id' => $model->for_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="formacion-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
