<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Habilidades $model */

$this->title = 'Crear Habilidades';
$this->params['breadcrumbs'][] = ['label' => 'Habilidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="habilidad-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
