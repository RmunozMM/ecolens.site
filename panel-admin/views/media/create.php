<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Media $model */

$this->title = 'Crear Media';
$this->params['breadcrumbs'][] = ['label' => 'Media', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'msg' => $msg,
    ]) ?>

</div>
