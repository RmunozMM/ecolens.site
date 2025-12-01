<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Modulo $model */

$this->title = 'Crear Módulo';
$this->params['breadcrumbs'][] = ['label' => 'Modulos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modulo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
