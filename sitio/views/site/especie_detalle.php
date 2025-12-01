<?php
/** @var yii\web\View $this */
/** @var object $especie */
/** @var object $taxonomia */

$this->title = $especie->esp_nombre_cientifico;
?>

<section class="detalle-especie">
  <div class="container">

    <nav class="breadcrumb">
      <a href="<?= Yii::$app->request->baseUrl ?>/">Inicio</a>
      <span class="separator">/</span>
      <a href="<?= Yii::$app->request->baseUrl ?>/taxonomias">Grupos taxonómicos</a>
      <span class="separator">/</span>
      <a href="<?= Yii::$app->request->baseUrl ?>/taxonomias/<?= $taxonomia->tax_slug ?>">
        <?= htmlspecialchars($taxonomia->tax_nombre_comun) ?>
      </a>
      <span class="separator">/</span>
      <span class="breadcrumb-current"><?= htmlspecialchars($especie->esp_nombre_cientifico) ?></span>
    </nav>

    <h1 class="titulo-especie"><?= htmlspecialchars($especie->esp_nombre_cientifico) ?></h1>
    <p class="subtitulo-especie">
      <strong>Nombre Común:</strong>
      <span class="latin"><?= htmlspecialchars($especie->esp_nombre_comun) ?></span>
    </p>

    <div class="imagen-especie mt-3 mb-4">
      <img src="<?= htmlspecialchars($especie->esp_imagen) ?>"
           alt="Imagen de <?= htmlspecialchars($especie->esp_nombre_comun) ?>"
           class="img-fluid">
    </div>

    <div class="descripcion mt-4">
      <?= $especie->esp_descripcion ?>
    </div>

  </div>
</section>

<style>
.detalle-especie {
  padding: 4rem 1rem;
  font-family: 'Nunito Sans', sans-serif;
}

.titulo-especie {
  font-size: 2.3rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
  color: #2b2b2b;
}

.subtitulo-especie {
  font-size: 1.1rem;
  color: #666;
  margin-bottom: 1.5rem;
}

.subtitulo-especie .latin {
  font-style: italic;
  color: #444;
}

.imagen-especie img {
  max-width: 100%;
  max-height: 500px;
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
  text-align: justify;
  margin-top: 2rem;
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
  align-items: center;
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

.breadcrumb-current {
  font-weight: bold;
  color: #2b2b2b;
}
</style>