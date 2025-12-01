<?php
/** @var yii\web\View $this */

$this->title = 'Taxonomías';

$contenido   = Yii::$app->view->params['contenido'] ?? (object)[];
$taxonomias  = $contenido->taxonomias ?? [];
$base        = Yii::$app->request->baseUrl;
?>

<section class="taxonomias">
  <div class="container">
    <h1 class="titulo-seccion">Grupos Taxonómicos</h1>
    <p class="intro">Explora los principales grupos de especies clasificadas en el proyecto.</p>

    <div class="taxo-grid">
      <?php foreach ($taxonomias as $taxo): ?>
        <div class="taxo-card">
          <div class="taxo-imagen">
            <img class="imagen_tax" src="<?= htmlspecialchars($taxo->tax_imagen) ?>" alt="Imagen de <?= htmlspecialchars($taxo->tax_nombre) ?>">
          </div>
          <div class="taxo-contenido">
            <h3><?= htmlspecialchars($taxo->tax_nombre_comun) ?></h3>
            <p class="nombre-latin"><?= htmlspecialchars($taxo->tax_nombre) ?></p>
                <a class="btn-taxo" href="<?= $base ?>/taxonomias/<?= urlencode($taxo->tax_slug) ?>">
                Ver <?= htmlspecialchars($taxo->tax_nombre_comun) ?>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<style>
.taxonomias {
  padding: 4rem 1rem;
  background: #fafafa;
  font-family: 'Nunito Sans', sans-serif;
}
.titulo-seccion {
  text-align: center;
  font-size: 2.5rem;
  margin-bottom: 0.5rem;
  color: #333;
}
.intro {
  text-align: center;
  font-size: 1.1rem;
  color: #666;
  margin-bottom: 3rem;
}
.taxo-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
  gap: 2rem;
}
.taxo-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
  overflow: hidden;
  transition: transform 0.2s ease;
}
.taxo-card:hover {
  transform: translateY(-5px);
}
.taxo-imagen img {
  width: 100%;
  height: 160px; /* antes era 180px o sin control */
  max-height: 160px;
  object-fit: cover;
  display: block;
}
.taxo-contenido {
  padding: 1rem;
}
.taxo-contenido h3 {
  font-size: 1.4rem;
  margin: 0 0 0.5rem;
  color: #222;
}
.nombre-latin {
  font-style: italic;
  font-size: 0.95rem;
  color: #888;
  margin-bottom: 0.5rem;
}
.descripcion {
  font-size: 0.95rem;
  color: #555;
  margin-bottom: 1rem;
}
.btn-taxo {
  display: inline-block;
  background-color: #45AD82;
  color: white;
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: bold;
  text-decoration: none;
  transition: background 0.3s ease;
}
.btn-taxo:hover {
  background-color: #3b8c6b;
}
</style>