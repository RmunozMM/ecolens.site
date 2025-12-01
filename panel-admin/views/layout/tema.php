<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Layout[] $layouts */

$this->title = 'Seleccionar Tema del Sitio';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="layouts-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="theme-grid">
        <?php foreach ($layouts as $layout): ?>
            <?php
                $isSelected = ($layout->sitioOpciones !== null);
                $cardClass = $isSelected ? 'theme-card selected' : 'theme-card';
            ?>
            <div class="theme-item">
                <div class="<?= $cardClass ?>">
                    <div class="theme-card-header">
                        <?= Html::encode($layout->lay_nombre) ?>
                        <?php if ($isSelected): ?>
                            <span class="badge-selected">Seleccionado</span>
                        <?php endif; ?>
                    </div>

                    <div class="gallery-row">
                        <?php
                            $rutaDirectorio = Yii::getAlias('@webroot') . '/temas/' . $layout->lay_ruta_imagenes;
                            $archivos = scandir($rutaDirectorio);
                            $extensionesPermitidas = ['jpg','jpeg','png','gif'];
                            $contador = 0;
                            foreach ($archivos as $archivo) {
                                $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
                                if (in_array($extension, $extensionesPermitidas)) {
                                    $imgUrl = Yii::getAlias('@web') . '/temas/' . $layout->lay_ruta_imagenes . '/' . $archivo;
                                    echo '<div class="gallery-item">';
                                    echo Html::a(
                                        Html::img($imgUrl, ['alt' => $archivo]),
                                        $imgUrl,
                                        [
                                            'data-fancybox' => 'gallery-' . $layout->lay_id,
                                            'data-caption' => Html::encode($layout->lay_nombre . ' - ' . $archivo),
                                        ]
                                    );
                                    echo '</div>';
                                    if (++$contador >= 3) break;
                                }
                            }
                        ?>
                    </div>

                    <div class="theme-card-footer">
                        <?php if ($isSelected): ?>
                            <?= Html::a('<i class="fa-solid fa-xmark"></i>', ['seleccionar', 'lay_id' => $layout->lay_id], [
                                'class' => 'btn-theme btn-deselect',
                                'title' => 'Deseleccionar tema',
                                'data' => ['confirm' => '¿Estás seguro de que deseas deseleccionar este tema?', 'method' => 'post'],
                            ]) ?>
                        <?php else: ?>
                            <?= Html::a('<i class="fa-solid fa-check"></i>', ['seleccionar', 'lay_id' => $layout->lay_id], [
                                'class' => 'btn-theme btn-select',
                                'title' => 'Seleccionar tema',
                                'data' => ['confirm' => '¿Estás seguro de que deseas seleccionar este tema?', 'method' => 'post'],
                            ]) ?>
                        <?php endif; ?>

                        <?= Html::a('<i class="fa-solid fa-palette"></i>', ['color/index', 'col_layout_id' => $layout->lay_id], [
                            'class' => 'btn-theme btn-colors',
                            'title' => 'Modificar colores',
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Fancybox y Masonry -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>

<style>
.layouts-index h1 {
    margin-bottom: 2rem;
    font-weight: 600;
    font-size: 1.75rem;
    text-align: center;
}
.theme-grid {
    display: flex;
    margin-left: -1rem;
    width: auto;
    flex-wrap: wrap;
}
.theme-item {
    margin-bottom: 2rem;
    padding-left: 1rem;
    width: 33.3333%;
    box-sizing: border-box;
}
@media (max-width: 768px) {
    .theme-item { width: 50%; }
}
@media (max-width: 576px) {
    .theme-item { width: 100%; }
}
.theme-card {
    border: 1px solid #ddd;
    border-radius: 6px;
    overflow: hidden;
    transition: transform 0.2s ease;
    background-color: #fff;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.theme-card:hover {
    transform: scale(1.01);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.theme-card.selected {
    border: 2px solid #27ae60;
}
.theme-card-header {
    padding: 0.75rem 1rem;
    background-color: #f7f7f7;
    border-bottom: 1px solid #ddd;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
}
.badge-selected {
    background-color: #27ae60;
    color: #fff;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
}
.gallery-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    padding: 1rem;
}
.gallery-item {
    width: 100%;
}
.gallery-item img {
    width: 100%;
    height: 140px;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #ccc;
}
.theme-card-footer {
    padding: 0.75rem 1rem;
    border-top: 1px solid #ddd;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.btn-theme {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 10px;
    border-radius: 4px;
    color: #fff;
    font-size: 1rem;
    text-decoration: none;
    transition: transform 0.2s ease;
}
.btn-theme:hover {
    transform: scale(1.1);
}
.btn-select { background-color: #27ae60; }
.btn-deselect { background-color: #c0392b; }
.btn-colors { background-color: #2dcacc; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var grid = document.querySelector('.theme-grid');
    if (grid) {
        new Masonry(grid, {
            itemSelector: '.theme-item',
            columnWidth: '.theme-item',
            percentPosition: true
        });
    }
    if (window.Fancybox) {
        Fancybox.bind('[data-fancybox]', {});
    }
});
</script>
