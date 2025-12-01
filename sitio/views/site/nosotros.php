<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* * NOTA: Asumo que las imágenes 'hero-ecolens.jpg' y 'arquitectura-ecolens.png' 
 * están en tu directorio 'web/images' o similar. 
 * Debes ajustar las rutas a donde tengas alojadas estas imágenes.
 * He usado las imágenes de tu PPT/Tesis.
 */
$heroImageUrl = Url::to('@web/images/hero-ecolens.jpg'); // Reemplazar con la ruta a la imagen de la Diapositiva 2 
$architectureImageUrl = Url::to('@web/images/arquitectura-ecolens.png'); // Reemplazar con la ruta a la imagen del Diagrama 

$this->title = 'Acerca de EcoLens';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-about">
    <section class="about-section container">
        
        <div class="header-image-wrapper">
            <img src="<?= $heroImageUrl ?>" alt="EcoLens en acción identificando fauna" class="hero-image">
            <h1 class="hero-title">Acerca del Proyecto EcoLens</h1>
        </div>
        
        <p class="project-intro">
            EcoLens es un proyecto de desarrollo tecnológico aplicado que diseña e
            implementa un prototipo funcional (TRL-4) de clasificación automática
            de fauna silvestre chilena, accesible desde dispositivos móviles,
            mediante la **Visión por Computador**.
        </p>

        <h2 class="section-title">El Desafío y la Arquitectura</h2>
        <div class="about-grid">
            <div class="mission-vision">
                <h3>El Desafío (Tesis)</h3>
                <p>
                    El objetivo central es validar la viabilidad de un sistema que
                    pueda identificar la **clase taxonómica** de un animal en tiempo
                    real, superando las limitaciones de la baja disponibilidad de
                    datos en fauna nativa chilena.
                </p>
            </div>
            <div class="mission-vision">
                <h3>Arquitectura del Sistema</h3>
                <p>
                    El sistema se basa en una arquitectura **Coarse-to-Fine (Router +
                    Expertos)**. El Modelo Router clasifica la Clase Taxonómica, y un
                    Modelo Experto (Ej: Mamíferos) se activa para refinar la
                    predicción.
                </p>
                <div class="architecture-image-wrapper">
                    <img src="<?= $architectureImageUrl ?>" alt="Diagrama de Arquitectura Coarse-to-Fine de EcoLens" class="architecture-image">
                </div>
            </div>
        </div>

        <h2 class="section-title" style="margin-top: 3rem">
            Métricas de Desempeño y Logros
        </h2>
        <p class="section-subtitle">
            El prototipo demuestra alta fiabilidad y eficiencia, cumpliendo los
            siguientes criterios clave de la tesis:
        </p>

        <div class="stats-grid">
            <div class="stat-item">
                <i class="fas fa-bullseye stat-icon"></i>
                <h3>Precisión de Clasificación</h3>
                <p>
                    El modelo experto (Mammalia) alcanzó un <strong>~94%</strong> de
                    accuracy en validación.
                </p>
            </div>
            <div class="stat-item">
                <i class="fas fa-database stat-icon"></i>
                <h3>Dataset de Entrenamiento</h3>
                <p>
                    Modelo entrenado con <strong>3000</strong> imágenes (300 por especie).
                </p>
            </div>
            <div class="stat-item">
                <i class="fas fa-tachometer-alt stat-icon"></i>
                <h3>Latencia Objetivo</h3>
                <p>
                    Respuesta (p95) &le; <strong>3s</strong> (logrando <strong>2.6s</strong> en piloto).
                </p>
            </div>
        </div>

        <h2 class="section-title" style="margin-top: 3rem">
            El Equipo detrás de EcoLens
        </h2>
        <p class="section-subtitle">
            Este proyecto fue desarrollado como trabajo de Tesis de Magíster en Ingeniería Informática (2024-2026) por:
        </p>

        <div class="team-grid">
            <div class="team-member-card">
                <img src="https://media.licdn.com/dms/image/v2/D4E03AQHP_Jp55wM4MA/profile-displayphoto-shrink_200_200/profile-displayphoto-shrink_200_200/0/1712606417415?e=1762992000&v=beta&t=ckGhOB4FJ1sgy8MrELtNWQD4SJz9XFVTNd_AKmr_XE0" alt="Foto de Rogelio Muñoz Muñoz" class="team-member-photo">
                <h3>Rogelio Muñoz Muñoz</h3>
                <p class="role">Arquitectura de Soluciones &amp; Desarrollo IA (Backend)</p>
                <p>
                    Ingeniero en Informática con más de 15 años de experiencia, especializado en arquitectura de soluciones, gestión de proyectos ágiles y desarrollo modular (CMS en Yii2).
                    Como investigador principal de EcoLens, Rogelio diseñó la arquitectura <em>Coarse-to-Fine</em>, lideró el entrenamiento de los modelos de IA y desarrolló la API y el backend del sistema.
                    Aporta su experiencia en Data Science y arquitecturas desacopladas para asegurar que EcoLens sea un prototipo escalable y robusto.
                </p>
                <a href="https://www.linkedin.com/in/rogeliomunozmunoz" target="_blank" class="linkedin-link">Ver perfil en LinkedIn</a>
            </div>

            <div class="team-member-card">
                <img src="https://media.licdn.com/dms/image/v2/C4E03AQEamMEZQDUdgA/profile-displayphoto-shrink_200_200/profile-displayphoto-shrink_200_200/0/1662725093661?e=1762992000&v=beta&t=wPVvCR33d6x-RmNVd7yONGDy7fSYhEf5HJoKIStGRBU" alt="Foto de Valeria Soriano Fernández" class="team-member-photo">
                <h3>Valeria Soriano Fernández</h3>
                <p class="role">Diseño de Interfaz &amp; Experiencia de Usuario (Frontend)</p>
                <p>
                    Ingeniera Civil Industrial y candidata a Magíster en Ingeniería Informática, con especialización en Ciencia de Datos e Ingeniería de Software Aplicada.
                    En el proyecto EcoLens, Valeria fue la responsable del diseño Frontend y la Experiencia de Usuario (UX).
                    Aportó su visión analítica y su formación en IA (Practitioner IBM) para traducir la complejidad del modelo en una interfaz web funcional y accesible, además de colaborar en la curaduría de datos y las pruebas de usabilidad.
                </p>
                <a href="https://www.linkedin.com/in/valeria-paz-soriano-fernández-2184a3231" target="_blank" class="linkedin-link">Ver perfil en LinkedIn</a>
            </div>
        </div>

        <a href="<?= Url::to(['/site/contact']) ?>" class="cta-button">Contáctanos o Colabora</a>
    </section>
</div>

<style>
    /* * ESTILOS REFINADOS - SIN PALETA DE COLORES PROPIA.
     * Estos estilos dependen de las variables CSS de tu sitio (ej. --primary-color).
     * Si no tienes --primary-color, reemplázalo por el color de tu logo (ej. #4CAF50).
    */

    /* --- Estructura General --- */
    .about-section {
        max-width: 1100px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .section-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #333; /* Un color oscuro base, no compite con el primario */
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

    /* --- Cabecera con Imagen --- */
    .header-image-wrapper {
        position: relative;
        width: 100%;
        max-height: 450px; /* Altura máxima para la imagen hero */
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

    /* --- Introducción --- */
    .project-intro {
        font-size: 1.15rem;
        line-height: 1.7;
        background-color: #f9f9f9; /* Color neutro */
        padding: 1.5rem 2rem;
        border-radius: 8px;
        border-left: 5px solid var(--primary-color, #4CAF50); /* Usa variable, con fallback al verde de tu logo */
        margin-bottom: 3rem;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    .project-intro strong {
        color: var(--primary-color, #4CAF50);
    }

    /* --- Grid de Contenido (Desafío y Arq.) --- */
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
        height: 100%; /* Asegura misma altura en el grid */
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

    /* --- Grid de Métricas --- */
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
        font-size: 1.4rem;
        margin-bottom: 0;
        font-weight: 600;
        color: #111;
    }

    /* --- Grid de Equipo --- */
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
        flex-grow: 1; /* Empuja el link de LinkedIn hacia abajo */
    }

    .team-member-card .linkedin-link {
        display: inline-block;
        margin-top: auto; /* Se alinea al fondo */
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

    /* --- Botón CTA --- */
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
        color: #ffffff; /* Asegura color en hover */
        text-decoration: none;
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.2);
        filter: brightness(110%); /* Un brillo sutil */
    }

    /* --- Media Queries (Responsividad) --- */
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