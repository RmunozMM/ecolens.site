<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Articulo $model */

$this->title = 'Crear Artículos';
$this->params['breadcrumbs'][] = ['label' => 'Artículos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="articulo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
