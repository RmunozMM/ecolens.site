<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \stdClass $contenido */
/** @var \stdClass $opciones */
$contenido = $this->params['contenido'] ?? (object)[];
$opciones  = $this->params['opciones']  ?? (object)[];

$articulos = $contenido->articulos ?? [];

$this->title = 'EcoLens: Explorando la Fauna Chilena con IA';
?>

<div class="site-index">
  <main>
    <!-- Hero principal -->
    <section class="hero-section-redesigned">
      <div class="hero-content-redesigned container">
        <h1 class="titulo">Descubre la Biodiversidad Chilena con EcoLens IA</h1>
        <p>
          Utiliza nuestra plataforma para identificar la clase taxonómica de la fauna 
          que encuentres en tus expediciones por Chile. Aprende y contribuye a la conservación.
        </p>
        <a href="<?= Url::to(['/registro']) ?>" class="cta-button">¡Comienza tu Aventura!</a>
      </div>
    </section>

    <!-- Presentación general -->
    <section class="what-is-ecolens container text-center">
      <h2>¿Qué es EcoLens?</h2>
      <p class="lead">
        EcoLens es un proyecto que une la Inteligencia Artificial con la pasión por la naturaleza chilena.
        Nuestra misión es entregar herramientas accesibles para el reconocimiento de especies y
        fomentar la educación ambiental en Chile. 
        <br><br>
        Sube una imagen, descubre su clasificación y aprende sobre el ecosistema que te rodea.
      </p>
    </section>

    <!-- Características principales -->
    <section class="features-section-redesigned container">
      <div class="features-grid-redesigned">
        <div class="feature-card-redesigned">
          <span class="feature-icon-redesigned">🔍</span>
          <h3>Clasificación Taxonómica</h3>
          <p>Identificamos 7 clases principales: Mamíferos, Aves, Reptiles, Anfibios, Insectos, Arácnidos y Peces.</p>
        </div>
        <div class="feature-card-redesigned">
          <span class="feature-icon-redesigned">📚</span>
          <h3>Enfoque Educativo</h3>
          <p>Promovemos la educación ambiental y una conexión profunda con el patrimonio natural de Chile.</p>
        </div>
        <div class="feature-card-redesigned">
          <span class="feature-icon-redesigned">⚡</span>
          <h3>Rápido y Validado (TRL-4)</h3>
          <p>Prototipo funcional validado en laboratorio, con clasificaciones en segundos (latencia p95 ≤ 3s).</p>
        </div>
      </div>
    </section>

    <!-- Base científica -->
    <section class="tech-section-redesigned container">
      <h2>Nuestra Base Científica y Tecnológica</h2>
      <div class="tech-grid-redesigned">
        <div class="tech-card-redesigned">
          <span class="tech-icon">📈</span>
          <h3>Precisión Comprobada</h3>
          <p>
            Modelo router con precisión global superior al <strong>92%</strong>;
            los modelos expertos, como Mamíferos, alcanzan <strong>94%</strong>.
          </p>
        </div>
        <div class="tech-card-redesigned">
          <span class="tech-icon">🧠</span>
          <h3>IA de Vanguardia</h3>
          <p>
            Usamos arquitecturas <strong>EfficientNet (B5 y B7)</strong> con Transfer Learning,
            optimizadas para la biodiversidad chilena.
          </p>
        </div>
        <div class="tech-card-redesigned">
          <span class="tech-icon">🌐</span>
          <h3>Arquitectura Escalable</h3>
          <p>Diseño modular <em>coarse-to-fine</em> que facilita agregar nuevas especies y modelos expertos.</p>
        </div>
      </div>
    </section>

    <!-- Equipo -->
    <section class="about-section-redesigned container">
      <h2>El Equipo Detrás de EcoLens</h2>
      <div class="about-content-redesigned">
        <div class="about-text-redesigned">
          <p>
            EcoLens es un <strong>proyecto de tesis de Magíster en Ingeniería Informática</strong>
            de la Universidad Andrés Bello. Nace de la necesidad de un sistema accesible y
            preciso para identificar fauna, contribuyendo a la conservación frente a la
            pérdida acelerada de biodiversidad en Chile.
          </p>
          <p>
            Este desarrollo fue realizado por <strong>Rogelio Muñoz Muñoz</strong> y 
            <strong>Valeria Soriano Fernández</strong>, bajo la supervisión del profesor 
            <strong>Miguel Solís Cid</strong>.
          </p>
            <a href="<?= Url::to(['/nosotros']) ?>" class="cta-button">Conoce más sobre nosotros</a>
          </a>
        </div>
        <div class="about-image-redesigned"></div>
      </div>
    </section>
  </main>
</div>

<!-- 🔧 Estilos complementarios específicos para esta vista -->
<style>
/* Se apoya en la paleta existente, sin redefinir variables globales */

.titulo{
color:#FFF;
}
.cta-button{cursor: pointer;}
/* --- HERO --- */
.hero-section-redesigned {
  background: linear-gradient(rgba(31,59,58,0.75), rgba(31,59,58,0.75)),
              url("/sitio/web//themes/default/assets/img/hero-bg2.png") center/cover no-repeat;
  color: #fff;
  padding: 120px 20px;
  text-align: center;
}
.hero-section-redesigned h1 {
  font-size: 3rem;
  font-family: "Lora", serif;
  margin-bottom: 1rem;
}
.hero-section-redesigned p {
  max-width: 700px;
  margin: 0 auto 2rem;
  font-size: 1.2rem;
}

/* --- FEATURES --- */
.features-grid-redesigned {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 2rem;
  margin-top: 2.5rem;
}
.feature-card-redesigned {
  background: var(--light-bg);
  border-radius: 10px;
  box-shadow: var(--card-shadow);
  padding: 2rem;
  text-align: center;
  width: 320px;
  transition: transform 0.3s ease;
}
.feature-card-redesigned:hover {
  transform: translateY(-6px);
}
.feature-icon-redesigned {
  font-size: 2.5rem;
  color: var(--primary-color);
  margin-bottom: 1rem;
}

/* --- TECH --- */
.tech-section-redesigned {
  text-align: center;
  background-color: var(--light-bg);
  padding: 4rem 2rem;
}
.tech-grid-redesigned {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 2rem;
}
.tech-card-redesigned {
  background: #fff;
  border-top: 4px solid var(--primary-color);
  border-radius: 10px;
  box-shadow: var(--card-shadow);
  padding: 2rem;
  width: 320px;
  text-align: left;
  transition: transform 0.3s ease;
}
.tech-card-redesigned:hover {
  transform: scale(1.03);
}
.tech-icon {
  font-size: 2rem;
  color: var(--secondary-color);
  margin-bottom: 0.8rem;
}

/* --- ABOUT --- */
.about-section-redesigned {
  background-color: var(--light-bg);
  padding: 4rem 2rem;
  text-align: center;
}
.about-content-redesigned {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 3rem;
  align-items: flex-start;
  margin-top: 2rem;
}
.about-text-redesigned {
  flex: 1 1 400px;
  text-align: left;
}
.about-text-redesigned p {
  margin-bottom: 1.2rem;
  color: var(--text-color);
}
.about-image-redesigned {
  flex: 1 1 300px;
  min-height: 250px;
  border-radius: 10px;
  background: url("/sitio/web//themes/default/assets/img/hero-bg.png") center/cover no-repeat,
              var(--light-bg);
  box-shadow: var(--card-shadow);
}

/* Responsividad */
@media (max-width: 768px) {
  .hero-section-redesigned h1 {font-size: 2.2rem;}
  .tech-card-redesigned, .feature-card-redesigned {width: 100%;}
  .about-content-redesigned {flex-direction: column;}
}
</style>