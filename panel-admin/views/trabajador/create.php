<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Trabajador $model */

$this->title = 'Crear Trabajadores';
$this->params['breadcrumbs'][] = ['label' => 'Trabajadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trabajador-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
