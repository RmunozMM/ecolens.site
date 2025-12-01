<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Leccion $model */

$this->title = 'Actualizar Lección: ' . $model->lec_id;
$this->params['breadcrumbs'][] = ['label' => 'Lecciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->lec_titulo, 'url' => ['view', 'lec_id' => $model->lec_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="leccion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
