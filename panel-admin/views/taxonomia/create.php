<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Taxonomia $model */

$this->title = 'Crear Taxonomía';
$this->params['breadcrumbs'][] = ['label' => 'Taxonomías', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="taxonomia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>