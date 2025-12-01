<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Media $model */


$this->title = 'Actualizar Media: ' . $model->med_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Media', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->med_nombre, 'url' => ['view', 'med_id' => $model->med_id]];
$this->params['breadcrumbs'][] = 'Actualizar';


?>
<div class="media-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'msg' => $msg,
    ]) ?>

</div>
