<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Acerca de EcoLens';
$this->params['breadcrumbs'][] = $this->title;

// Rutas en duro al estilo “no me voy a calentar la cabeza”
$heroImageUrl         = '/sitio/web/themes/default/assets/img/hero-ecolens.png';
$architectureImageUrl = '/sitio/web/themes/default/assets/img/arquitectura-ecolens.png';
$fotoRogelio          = '/sitio/web/themes/default/assets/img/equipo-rogelio.jpeg';
$fotoValeria          = '/sitio/web/themes/default/assets/img/equipo-valeria.jpeg';
?>

<div class="site-about">
    <section class="about-section container">
        
        <div class="header-image-wrapper">
            <img src="<?= Html::encode($heroImageUrl) ?>" 
                 alt="EcoLens identificando fauna en parques nacionales" 
                 class="hero-image">
            <h1 class="hero-title">Acerca del Proyecto EcoLens</h1>
        </div>
        
        <p class="project-intro">
            EcoLens es un proyecto de desarrollo tecnológico aplicado que diseña e 
            implementa un prototipo funcional de clasificación automática de fauna
            silvestre chilena, accesible desde dispositivos móviles, mediante 
            técnicas de <strong>visión por computador</strong>.
            El sistema alcanza <strong>TRL-4</strong> en laboratorio y evidencia
            capacidades de <strong>TRL-5</strong> al operar en un entorno relevante.
        </p>

        <h2 class="section-title">El desafío y la arquitectura</h2>
        <div class="about-grid">
            <div class="mission-vision">
                <h3>El desafío (tesis)</h3>
                <p>
                    El objetivo central es validar la viabilidad de un sistema que
                    pueda identificar la <strong>clase taxonómica</strong> de un
                    animal en tiempo casi real, trabajando con la baja 
                    disponibilidad de datos de fauna nativa chilena y las 
                    restricciones de conectividad y recursos en terreno.
                </p>
                <p>
                    El prototipo se orienta al uso en <strong>parques nacionales y
                    áreas protegidas</strong>, ofreciendo una herramienta que 
                    conecta visitantes, guardaparques e instituciones con datos
                    estructurados sobre biodiversidad.
                </p>
            </div>
            <div class="mission-vision">
                <h3>Arquitectura del sistema</h3>
                <p>
                    EcoLens se basa en una arquitectura jerárquica
                    <strong>Coarse-to-Fine (Router + Expertos)</strong>. 
                    Un modelo router generalista clasifica la clase taxonómica 
                    (Mamíferos, Aves, Reptiles, Anfibios, Insectos, Arácnidos, Peces) 
                    y, según corresponda, activa un modelo experto especializado 
                    (por ejemplo, <strong>Mammalia</strong>) para refinar la
                    predicción.
                </p>
                <p>
                    Esta separación permite escalar el sistema: se pueden incorporar
                    nuevos expertos por grupo taxonómico sin reentrenar todo el
                    pipeline, manteniendo un punto único de entrada para el usuario.
                </p>
                <div class="architecture-image-wrapper">
                    <img src="<?= Html::encode($architectureImageUrl) ?>" 
                         alt="Diagrama de arquitectura Coarse-to-Fine de EcoLens" 
                         class="architecture-image">
                </div>
            </div>
        </div>

        <h2 class="section-title" style="margin-top: 3rem">
            Métricas de desempeño y logros
        </h2>
        <p class="section-subtitle">
            El prototipo demuestra alta fiabilidad y eficiencia, cumpliendo los
            principales criterios técnicos definidos en la tesis.
        </p>

        <div class="stats-grid">
            <div class="stat-item">
                <i class="fas fa-bullseye stat-icon"></i>
                <h3>Desempeño de clasificación</h3>
                <p>
                    El modelo router generalista alcanza 
                    <strong>accuracy ≈ 0,92</strong> y 
                    <strong>F1-macro ≈ 0,89</strong>. 
                    El modelo experto para Mamíferos logra 
                    <strong>F1-macro ≈ 0,90</strong> en validación con datos no vistos.
                </p>
            </div>
            <div class="stat-item">
                <i class="fas fa-database stat-icon"></i>
                <h3>Curaduría de datos</h3>
                <p>
                    Entrenamiento realizado sobre un conjunto de alrededor de 
                    <strong>3&nbsp;000 imágenes curadas</strong> desde iNaturalist, 
                    priorizando especies nativas y endémicas y depurando ruido, 
                    duplicados y clasificaciones dudosas.
                </p>
            </div>
            <div class="stat-item">
                <i class="fas fa-tachometer-alt stat-icon"></i>
                <h3>Latencia y experiencia de uso</h3>
                <p>
                    El sistema cumple con una latencia objetivo de 
                    <strong>p95 ≤ 3&nbsp;s</strong>, logrando aproximadamente 
                    <strong>2,6&nbsp;s</strong> en el piloto desplegado, con una 
                    disponibilidad superior al <strong>97&nbsp;%</strong> durante
                    el periodo de pruebas.
                </p>
            </div>
        </div>

        <h2 class="section-title" style="margin-top: 3rem">
            El equipo detrás de EcoLens
        </h2>
        <p class="section-subtitle">
            Proyecto desarrollado como Trabajo de Tesis del 
            <strong>Magíster en Ingeniería Informática</strong> (Universidad Andrés Bello, 2024–2026),
            integrando investigación aplicada, ingeniería de software e inteligencia artificial.
        </p>

        <div class="team-grid">
            <div class="team-member-card">
                <img src="<?= Html::encode($fotoRogelio) ?>" 
                     alt="Foto de Rogelio Muñoz Muñoz" 
                     class="team-member-photo">
                <h3>Rogelio Muñoz Muñoz</h3>
                <p class="role">Arquitectura de soluciones &amp; backend IA</p>
                <p>
                    Ingeniero en Informática con más de 15 años de experiencia en 
                    arquitectura de soluciones, gestión de proyectos ágiles y 
                    desarrollo modular (CMS en Yii2). 
                    En EcoLens se desempeña como <strong>investigador principal</strong>,
                    responsable del diseño de la arquitectura <em>Coarse-to-Fine</em>,
                    del entrenamiento y orquestación de los modelos de IA, y del 
                    desarrollo del backend y las APIs que conectan el modelo con
                    el portal web y el panel de monitoreo.
                </p>
                <a href="https://www.linkedin.com/in/rogeliomunozmunoz" 
                   target="_blank" 
                   rel="noopener" 
                   class="linkedin-link">
                    Ver perfil en LinkedIn
                </a>
            </div>

            <div class="team-member-card">
                <img src="<?= Html::encode($fotoValeria) ?>" 
                     alt="Foto de Valeria Soriano Fernández" 
                     class="team-member-photo">
                <h3>Valeria Soriano Fernández</h3>
                <p class="role">Diseño de interfaz &amp; experiencia de usuario</p>
                <p>
                    Ingeniera Civil Industrial y candidata a Magíster en Ingeniería
                    Informática, con formación en Ciencia de Datos e Ingeniería de 
                    Software Aplicada. En EcoLens lidera el diseño de interfaz y 
                    <strong>experiencia de usuario</strong>, traduciendo la complejidad 
                    del modelo en un flujo de uso simple y accesible. 
                    Además, colabora en la curaduría de datos, la definición de 
                    requerimientos y las pruebas de usabilidad del prototipo web.
                </p>
                <a href="https://www.linkedin.com/in/valeria-paz-soriano-fernández-2184a3231" 
                   target="_blank" 
                   rel="noopener" 
                   class="linkedin-link">
                    Ver perfil en LinkedIn
                </a>
            </div>
        </div>

        <a href="<?= Url::to(['/contacto']) ?>" class="cta-button">
            Contáctanos o colabora con EcoLens
        </a>
    </section>
</div>

<style>
    .about-section {
        max-width: 1100px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .section-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #333;
        text-align: center;
        border-bottom: 3px solid #eee;
        padding-bottom: 15px;
        margin-top: 3.5rem;
        margin-bottom: 1.5rem;
    }

    .section-subtitle {
        text-align: center;
        font-size: 1.1rem;
        color: #555;
        margin-top: -1rem;
        margin-bottom: 2.5rem;
    }

    .header-image-wrapper {
        position: relative;
        width: 100%;
        max-height: 450px;
        overflow: hidden;
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        margin-bottom: 2.5rem;
    }

    .hero-image {
        width: 100%;
        height: auto;
        object-fit: cover;
        display: block;
    }

    .hero-title {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        margin: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7), rgba(0,0,0,0));
        color: white;
        padding: 2.5rem 2rem 1.5rem 2rem;
        font-size: 2.8rem;
        font-weight: 700;
    }

    .project-intro {
        font-size: 1.15rem;
        line-height: 1.7;
        background-color: #f9f9f9;
        padding: 1.5rem 2rem;
        border-radius: 8px;
        border-left: 5px solid var(--primary-color, #4CAF50);
        margin-bottom: 3rem;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    .project-intro strong {
        color: var(--primary-color, #4CAF50);
    }

    .about-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2.5rem;
        align-items: flex-start;
    }

    .mission-vision {
        background-color: #ffffff;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        height: 100%;
    }

    .mission-vision h3 {
        color: var(--primary-color, #4CAF50);
        font-size: 1.5rem;
        margin-top: 0;
        margin-bottom: 1rem;
    }
    
    .mission-vision p {
        font-size: 1rem;
        line-height: 1.6;
    }

    .architecture-image-wrapper {
        margin-top: 1.5rem;
        text-align: center;
    }

    .architecture-image {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        text-align: center;
        margin-top: 2.5rem;
        margin-bottom: 4rem;
    }

    .stat-item {
        background-color: #ffffff;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.12);
    }

    .stat-item .stat-icon {
        font-size: 2.5rem;
        color: var(--primary-color, #4CAF50);
        margin-bottom: 1rem;
    }

    .stat-item h3 {
        margin-top: 0.5rem;
        font-size: 1.3rem;
        color: #333;
        margin-bottom: 0.8rem;
    }
    
    .stat-item p {
        font-size: 0.98rem;
        margin-bottom: 0;
        color: #111;
    }

    .team-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2.5rem;
        margin-top: 2rem;
        margin-bottom: 4rem;
    }

    .team-member-card {
        background-color: #ffffff;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .team-member-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.12);
    }

    .team-member-photo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 1.5rem auto;
        border: 4px solid var(--primary-color, #4CAF50);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .team-member-card h3 {
        margin-top: 0;
        color: var(--primary-color, #4CAF50);
        font-size: 1.6rem;
        margin-bottom: 0.25rem;
    }

    .team-member-card .role {
        font-weight: 700;
        color: #555;
        font-size: 1rem;
        margin-bottom: 1.5rem;
        display: block;
    }

    .team-member-card p {
        font-size: 0.95rem;
        line-height: 1.7;
        color: #333;
        text-align: left;
        margin-bottom: 1.5rem;
        flex-grow: 1;
    }

    .team-member-card .linkedin-link {
        display: inline-block;
        margin-top: auto;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        color: var(--primary-color, #4CAF50);
        border: 2px solid var(--primary-color, #4CAF50);
        padding: 0.7rem 1.5rem;
        border-radius: 50px;
        transition: all 0.3s ease;
    }

    .team-member-card .linkedin-link:hover {
        background-color: var(--primary-color, #4CAF50);
        color: #ffffff;
        text-decoration: none;
    }

    .cta-button {
        display: block;
        width: fit-content;
        margin: 2rem auto;
        background-color: var(--primary-color, #4CAF50);
        color: #ffffff;
        padding: 1rem 2.5rem;
        font-size: 1.2rem;
        font-weight: 700;
        text-align: center;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .cta-button:hover {
        color: #ffffff;
        text-decoration: none;
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.2);
        filter: brightness(110%);
    }

    @media (max-width: 992px) {
        .about-grid {
            grid-template-columns: 1fr;
        }
        .team-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
            padding: 2rem 1.5rem 1rem 1.5rem;
        }
        .section-title {
            font-size: 1.8rem;
        }
        .project-intro {
            font-size: 1.05rem;
        }
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
