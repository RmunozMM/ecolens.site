<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Curso $model */

$this->title = 'Actualizar Curso: ' . $model->cur_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Cursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cur_titulo, 'url' => ['view', 'cur_id' => $model->cur_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="curso-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
