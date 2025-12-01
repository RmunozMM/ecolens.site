<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Newsletter $model */

$this->title = 'Create Newsletter';
$this->params['breadcrumbs'][] = ['label' => 'Newsletters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="newsletter-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
