<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Galerias $model */

$this->title = 'Actualizar: ' . $model->gal_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Galerias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->gal_titulo, 'url' => ['view', 'gal_id' => $model->gal_id]];
$this->params['breadcrumbs'][] = 'Actualizar';


?>
<div class="galerias-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'imagenes' => $imagenes,
    ]) ?>

</div>
