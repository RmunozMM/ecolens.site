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
      <a href="<?= Yii::$app->request->baseUrl ?>/taxonomias/<?= htmlspecialchars($taxonomia->tax_slug) ?>">
        <?= htmlspecialchars($taxonomia->tax_nombre_comun) ?>
      </a>
      <span class="separator">/</span>
      <span class="breadcrumb-current"><?= htmlspecialchars($especie->esp_nombre_cientifico) ?></span>
    </nav>

    <h1 class="titulo-especie"><?= htmlspecialchars($especie->esp_nombre_cientifico) ?></h1>
    <p class="subtitulo-especie">
      <strong>Nombre común:</strong>
      <span class="latin"><?= htmlspecialchars($especie->esp_nombre_comun) ?></span>
    </p>

    <!-- Layout imagen + descripción -->
    <div class="especie-layout">
      <div class="especie-imagen">
        <img src="<?= htmlspecialchars($especie->esp_imagen) ?>"
             alt="Imagen de <?= htmlspecialchars($especie->esp_nombre_comun) ?>"
             class="img-fluid">
      </div>

      <div class="especie-texto">
        <div class="descripcion">
          <?= $especie->esp_descripcion ?>
        </div>
      </div>
    </div>

  </div>
</section>

<style>
.detalle-especie {
  padding: 4rem 1rem;
  font-family: 'Nunito Sans', sans-serif;
}

/* Título y subtítulo */
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

/* Layout principal: imagen izquierda, texto derecha en desktop */
.especie-layout {
  display: grid;
  grid-template-columns: minmax(260px, 320px) minmax(0, 1fr);
  gap: 2rem;
  align-items: flex-start;
}

/* Imagen */
.especie-imagen img {
  width: 100%;
  max-height: 420px;
  object-fit: cover;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  display: block;
}

/* Texto / descripción */
.especie-texto .descripcion {
  font-size: 1.05rem;
  color: #444;
  line-height: 1.7;
  text-align: justify;
}

/* Breadcrumb (mismo estilo que taxonomías) */
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

/* Responsive: en móviles se apila */
@media (max-width: 768px) {
  .especie-layout {
    grid-template-columns: 1fr;
  }

  .especie-imagen img {
    max-height: 320px;
  }

  .titulo-especie {
    font-size: 2rem;
  }
}
</style>
