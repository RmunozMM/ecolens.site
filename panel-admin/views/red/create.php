<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Redes $model */

$this->title = 'Crear Redes Sociales';
$this->params['breadcrumbs'][] = ['label' => 'Redes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="redes-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
