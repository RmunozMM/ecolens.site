<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Recurso $model */

$this->title = 'Crear Recurso';
$this->params['breadcrumbs'][] = ['label' => 'Recursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recurso-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
