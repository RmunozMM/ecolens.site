<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CategoriaServicio $model */

$this->title = 'Crear Categorias de Servicio';
$this->params['breadcrumbs'][] = ['label' => 'Categorias de Servicio', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categoria-servicio-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>