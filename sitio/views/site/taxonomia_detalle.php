<?php
/** @var yii\web\View $this */
/** @var object $taxonomia */
/** @var array $especies */

use yii\helpers\Inflector;

$this->title = $taxonomia->tax_nombre_comun;
?>

<section class="detalle-taxonomia">
  <div class="container">
    <nav class="breadcrumb">
      <a href="<?= Yii::$app->request->baseUrl ?>/">Inicio</a>
      <span class="separator">/</span>
      <a href="<?= Yii::$app->request->baseUrl ?>/taxonomias">Grupos taxonómicos</a>
      <span class="separator">/</span>
      <span><?= htmlspecialchars($taxonomia->tax_nombre_comun) ?></span>
    </nav>

    <h1 class="titulo-taxonomia"><?= htmlspecialchars($taxonomia->tax_nombre_comun) ?></h1>
    <p class="subtitulo-taxonomia">
      <strong>Nombre científico:</strong>
      <span class="latin"><?= htmlspecialchars($taxonomia->tax_nombre) ?></span>
    </p>

    <div class="imagen-taxonomia">
      <img src="<?= htmlspecialchars($taxonomia->tax_imagen) ?>"
           alt="Imagen de <?= htmlspecialchars($taxonomia->tax_nombre) ?>"
           class="img-fluid">
    </div>

    <div class="descripcion mt-4">
      <?= $taxonomia->tax_descripcion ?>
    </div>

    <?php if (!empty($especies)): ?>
      <div class="especies-relacionadas mt-5">
        <h2>Especies clasificadas en este grupo</h2>
        <div class="row">
          <?php foreach ($especies as $esp): ?>
            <?php
              $nombreComun = $esp->esp_nombre_comun ?? '';
              $nombreCientifico = $esp->esp_nombre_cientifico ?? '';
              $imagen = $esp->esp_imagen;
              $slugTax = $taxonomia->tax_slug;

              // 🔹 Corregido: usar nombre científico para el slug
              $slugEsp = Inflector::slug($nombreCientifico);
              $base = Yii::$app->request->baseUrl;
              $url = "{$base}/taxonomias/{$slugTax}/{$slugEsp}";
            ?>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
              <a href="<?= $url ?>" class="card h-100 especie-card text-decoration-none text-dark">
                <img 
                  src="<?= htmlspecialchars($imagen) ?>" 
                  alt="<?= htmlspecialchars($nombreComun ?: $nombreCientifico) ?>" 
                  class="card-img-top"
                  style="height: 180px; object-fit: cover; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
                <div class="card-body text-center py-3">
                  <h6 class="mb-0"><?= htmlspecialchars($nombreComun ?: '—') ?></h6>
                  <small class="text-muted"><em><?= htmlspecialchars($nombreCientifico) ?></em></small>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<style>
.detalle-taxonomia {
  padding: 4rem 1rem;
  font-family: 'Nunito Sans', sans-serif;
}

.titulo-taxonomia {
  font-size: 2.3rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
  color: #2b2b2b;
}

.subtitulo-taxonomia {
  font-size: 1.1rem;
  color: #666;
  margin-bottom: 1.5rem;
}

.subtitulo-taxonomia .latin {
  font-style: italic;
  color: #444;
}

.imagen-taxonomia img {
  max-width: 100%;
  max-height: 400px;
  object-fit: cover;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  display: block;
  margin: 0 auto;
}

.descripcion {
  font-size: 1.05rem;
  color: #444;
  line-height: 1.6;
  margin-top: 2rem;
  text-align: justify;
}

.breadcrumb {
  background: #f1f3f5;
  padding: 0.75rem 1rem;
  font-size: 0.95rem;
  border-radius: 6px;
  color: #555;
  margin-bottom: 1.5rem;
  display: flex;
  flex-wrap: wrap;
  gap: 0.4rem;
}

.breadcrumb a {
  color: #2e7d32;
  text-decoration: none;
  font-weight: 500;
}

.breadcrumb a:hover {
  text-decoration: underline;
}

.breadcrumb .separator {
  color: #999;
}

.especie-card {
  border: 1px solid #e0e0e0;
  border-radius: .5rem;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.especie-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 16px rgba(0,0,0,0.08);
}
</style>