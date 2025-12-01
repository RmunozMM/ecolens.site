<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ImagenesGaleria $model */
/** @var string $titulo */

$this->title = 'Agregar Imágenes a: ' . $titulo;
$this->params['breadcrumbs'][] = [
    'label' => $titulo,
    'url'   => ['galeria/view', 'gal_id' => Yii::$app->request->get('gal_id')]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imagenes-galeria-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <!-- Renderiza la vista parcial _form con $model -->
    <?= $this->render('_form', [
        'model'  => $model,
        'titulo' => $titulo
    ]) ?>
</div>