<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Deteccion $model */

$this->title = 'Crear Detección';
$this->params['breadcrumbs'][] = ['label' => 'Detecciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deteccion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>