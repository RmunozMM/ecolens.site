<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CategoriaArticulo $model */

$this->title = 'Create Categoria Articulo';
$this->params['breadcrumbs'][] = ['label' => 'Categoria Articulos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categoria-articulo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
