<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ImagenesGaleria $model */

$this->title = 'Update Imagenes Galeria: ' . $model->img_id;
$this->params['breadcrumbs'][] = ['label' => 'Imagenes Galerias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->img_id, 'url' => ['view', 'img_id' => $model->img_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="imagenes-galeria-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <!-- Renderiza la misma vista parcial _form -->
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>