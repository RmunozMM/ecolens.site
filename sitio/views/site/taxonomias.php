<?php
/** @var yii\web\View $this */

$this->title = 'Taxonomías';

$contenido   = Yii::$app->view->params['contenido'] ?? (object)[];
$taxonomias  = $contenido->taxonomias ?? [];
$base        = Yii::$app->request->baseUrl;
?>

<section class="taxonomias">
  <div class="container">
    <h1 class="titulo-seccion">Grupos taxonómicos que EcoLens reconoce</h1>

    <p class="intro">
      En EcoLens organizamos la fauna en <strong>grupos taxonómicos</strong>, es decir, conjuntos de especies
      que comparten características biológicas en común. 
      Los grupos que ves a continuación corresponden a las <strong>clases taxonómicas</strong> 
      que el modelo es capaz de identificar en su versión actual, entrenado principalmente con
      <strong>especies presentes en Chile</strong>.
    </p>

    <div class="taxo-def">
      <h2>¿Qué es una taxonomía?</h2>
      <p>
        La <strong>taxonomía</strong> es la disciplina que clasifica a los seres vivos en categorías jerárquicas
        (como reino, filo, clase, orden, familia, género y especie) para entender mejor sus relaciones y evolución.
      </p>
      <p>
        EcoLens trabaja a nivel de <strong>clase taxonómica</strong>: por ejemplo, Mamíferos, Aves, Reptiles,
        Anfibios, Insectos, Arácnidos y Peces. A partir de esa clase, el sistema puede enlazar con
        <strong>especies concretas</strong> dentro de cada grupo, priorizando fauna nativa y endémica chilena.
      </p>
      <p class="note">
        En términos simples: cada tarjeta representa un “gran grupo” de animales que EcoLens puede reconocer
        a partir de una fotografía cargada por la persona usuaria.
      </p>
      <p class="note invite">
        Te invitamos a explorar estos grupos haciendo clic en las fichas de más abajo:
        en cada una encontrarás una descripción general y enlaces hacia las especies asociadas.
      </p>
    </div>

    <div class="taxo-grid">
      <?php foreach ($taxonomias as $taxo): ?>
        <div class="taxo-card">
          <div class="taxo-imagen">
            <img
              class="imagen_tax"
              src="<?= htmlspecialchars($taxo->tax_imagen) ?>"
              alt="Imagen de <?= htmlspecialchars($taxo->tax_nombre_comun ?: $taxo->tax_nombre) ?>">
          </div>
          <div class="taxo-contenido">
            <h3><?= htmlspecialchars($taxo->tax_nombre_comun) ?></h3>
            <p class="nombre-latin"><?= htmlspecialchars($taxo->tax_nombre) ?></p>

            <?php if (!empty($taxo->tax_resumen)): ?>
              <p class="descripcion">
                <?= htmlspecialchars($taxo->tax_resumen) ?>
              </p>
            <?php endif; ?>

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
  color: #555;
  margin: 0 auto 2.5rem;
  max-width: 820px;
}

/* Bloque explicativo de taxonomía */
.taxo-def {
  max-width: 900px;
  margin: 0 auto 3rem;
  background: #ffffff;
  border-radius: 12px;
  border-left: 5px solid #45AD82;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  padding: 1.8rem 2rem;
}

.taxo-def h2 {
  margin-top: 0;
  font-size: 1.6rem;
  color: #333;
  margin-bottom: 0.8rem;
}

.taxo-def p {
  margin: 0 0 0.8rem;
  font-size: 0.98rem;
  color: #555;
  line-height: 1.6;
}

.taxo-def .note {
  margin-top: 0.8rem;
  font-size: 0.95rem;
  color: #444;
  font-style: italic;
}

.taxo-def .note.invite {
  margin-top: 0.6rem;
  font-style: normal;
  font-weight: 600;
  color: #333;
}

/* Grid de tarjetas */
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
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.taxo-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 14px rgba(0,0,0,0.12);
}

.taxo-imagen img {
  width: 100%;
  height: 160px;
  max-height: 160px;
  object-fit: cover;
  display: block;
}

.taxo-contenido {
  padding: 1rem;
}

.taxo-contenido h3 {
  font-size: 1.4rem;
  margin: 0 0 0.4rem;
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
  margin-bottom: 0.9rem;
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

/* Responsivo */
@media (max-width: 768px) {
  .taxo-def {
    padding: 1.4rem 1.4rem;
  }
}
</style>
