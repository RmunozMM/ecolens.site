<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Leccion $model */

$this->title = 'Crear Lección';
$this->params['breadcrumbs'][] = ['label' => 'Lecciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leccion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
