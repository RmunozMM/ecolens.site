<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Modelo $model */

$this->title = 'Create Modelo';
$this->params['breadcrumbs'][] = ['label' => 'Modelos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modelo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
