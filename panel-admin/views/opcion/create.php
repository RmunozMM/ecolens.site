<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Opcion $model */

$this->title = 'Create Opcion';
$this->params['breadcrumbs'][] = ['label' => 'Opcions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="opcion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
