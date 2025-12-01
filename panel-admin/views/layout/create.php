<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Layouts $model */

$this->title = 'Create Layouts';
$this->params['breadcrumbs'][] = ['label' => 'Layouts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="layouts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
