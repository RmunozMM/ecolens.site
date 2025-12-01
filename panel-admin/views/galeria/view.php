<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AuditoriaGridColumns;
use app\helpers\LibreriaHelper;



/** @var yii\web\View $this */
/** @var app\models\Galerias $model */

$this->title = $model->gal_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Galerías', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="galerias-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'gal_id' => $model->gal_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'gal_id' => $model->gal_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de querer eliminar este ítem? Esta acción no se puede deshacer.',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Ir al registro principal', [strtolower($model->gal_tipo_registro) . '/view', strtolower(substr($model->gal_tipo_registro, 0, 3)) . '_id' => $model->gal_id_registro], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'gal_titulo',
            [
                'attribute' => 'gal_descripcion',
                'format' => 'html',
            ],
            'gal_estado',
        ], AuditoriaGridColumns::getAuditoriaAttributes($model)),
    ]) ?>
</div>

<!-- Fancybox Recursos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

<div class="contenedor_imagenes">
    <p>Imágenes que tiene la galería:</p>
    <div class="imagenes-container">
        <?php foreach ($imagenes as $index => $imagen): ?>
            <a href="<?= LibreriaHelper::getRecursos(). "uploads/" . $imagen->img_ruta ?>" 
               data-fancybox="grupo-imagenes"
               data-caption="Imagen <?= $index + 1 ?>"
               class="imagen-container fancybox">
                <img class="imagen" src="<?= LibreriaHelper::getRecursos(). "uploads/" . $imagen->img_ruta ?>" alt="Imagen <?= $index + 1 ?>">
            </a>
        <?php endforeach; ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".fancybox").fancybox({
            loop: true,
            buttons: ["slideShow", "fullScreen", "thumbs", "close"],
            idleTime: false,
            animationEffect: "fade",
            transitionEffect: "slide",
            transitionDuration: 600,
            protect: true,
            hideScrollbar: false
        });
    });
</script>

<style>
.contenedor_imagenes {
    padding-top: 30px;
}
.imagen-container {
    width: 200px;
    height: 150px;
    overflow: hidden;
    margin-right: 10px;
}
.imagen {
    width: 100%;
    height: auto;
}
.imagenes-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
}
</style>