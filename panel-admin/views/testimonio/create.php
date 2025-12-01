<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Testimonio $model */

$this->title = 'Crear Testimonio';
$this->params['breadcrumbs'][] = ['label' => 'Testimonios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="testimonio-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
