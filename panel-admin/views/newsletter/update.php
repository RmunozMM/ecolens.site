<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Newsletter $model */

$this->title = 'Actualizar Suscrito al Newsletter: ' . $model->new_email;
$this->params['breadcrumbs'][] = ['label' => 'Newsletters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->new_email, 'url' => ['view', 'new_id' => $model->new_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="newsletter-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
