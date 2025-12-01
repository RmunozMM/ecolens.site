<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Modelo $model */

$this->title = 'Update Modelo: ' . $model->mod_id;
$this->params['breadcrumbs'][] = ['label' => 'Modelos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mod_id, 'url' => ['view', 'mod_id' => $model->mod_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="modelo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
