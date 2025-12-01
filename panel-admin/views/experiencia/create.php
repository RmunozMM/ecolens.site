<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Experiencias $model */

$this->title = 'Crear Experiencias';
$this->params['breadcrumbs'][] = ['label' => 'Experiencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="experiencias-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
