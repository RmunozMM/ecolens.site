<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CategoriaOpcion $model */

$this->title = 'Create Categoria Opcion';
$this->params['breadcrumbs'][] = ['label' => 'Categoria Opcions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categoria-opcion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
