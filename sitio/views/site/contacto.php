<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Contacto"; // Título de la página


// Mensajes de éxito o error (usados si backend redirige con flash)
$success = Yii::$app->session->getFlash('success');
$error   = Yii::$app->session->getFlash('error');

// Datos de contacto (perfil)
$perfil = Yii::$app->view->params['contenido']->perfil ?? null;

$email      = $perfil->per_correo       ?? null;
$telefono   = $perfil->per_telefono     ?? null;
$ubicacion  = $perfil->per_nacionalidad ?? null;
$linkedin   = $perfil->per_linkedin     ?? null;
$sitioweb   = $perfil->per_sitio_web    ?? null;

// Asuntos desde la API
$asuntos = Yii::$app->view->params['contenido']->asuntos ?? [];

// Calcula la URL de la API (dinámicamente, sin hardcodear)
$apiContactoUrl = str_replace(
    '/sitio/web/',
    '/panel-admin/web/',
    Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . '/'
) . 'api/contacto/contacto';
?>
<main class="page-content">
    <section id="contact-full" class="contact-section" style="padding: 60px 0;">
        <div class="container">
            <h2 class="page-title">Contacto</h2>
            <div class="contact-wrapper" style="display: flex; flex-wrap: wrap; gap: 2.5rem;">

                <!-- Información de contacto -->
                <div class="contact-info" style="flex:1 1 320px; min-width: 300px;">
                    <h3>Información de Contacto</h3>
                    <p>
                        <strong>EcoLens</strong> es una plataforma de inteligencia artificial para la detección y 
                        clasificación de fauna silvestre chilena. Nuestro objetivo es contribuir a la investigación, 
                        educación y conservación de la biodiversidad mediante tecnología accesible y colaborativa.
                    </p>
                    <p>
                        Si deseas colaborar, integrar nuestros modelos en un proyecto institucional, o 
                        aportar datos para mejorar el reconocimiento de especies, puedes escribirnos a través 
                        de los siguientes medios:
                    </p>

                    <ul style="padding-left: 0; list-style: none;">
                        <?php if ($email): ?>
                            <li>
                                <strong>Email:</strong>
                                <a href="mailto:<?= Html::encode($email) ?>"><?= Html::encode($email) ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($telefono): ?>
                            <li>
                                <strong>Teléfono:</strong>
                                <a href="tel:<?= Html::encode($telefono) ?>"><?= Html::encode($telefono) ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($ubicacion): ?>
                            <li>
                                <strong>Ubicación:</strong>
                                <?= Html::encode($ubicacion) ?>
                            </li>
                        <?php endif; ?>
                        <?php if ($linkedin): ?>
                            <li>
                                <strong>LinkedIn:</strong>
                                <a href="<?= Html::encode($linkedin) ?>" target="_blank" rel="noopener"><?= Html::encode($linkedin) ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($sitioweb): ?>
                            <li>
                                <strong>Sitio Web:</strong>
                                <a href="<?= Html::encode($sitioweb) ?>" target="_blank" rel="noopener"><?= Html::encode($sitioweb) ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>

                    <p>
                        Responderemos todas las consultas relacionadas con investigación, colaboración, 
                        desarrollo o divulgación científica dentro del proyecto.
                    </p>
                </div>

                <!-- Formulario de contacto -->
                <div class="contact-form-container" style="flex:2 1 480px; min-width: 340px;">
                    <h3>Envíanos tu mensaje</h3>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= Html::encode($success) ?></div>
                    <?php elseif ($error): ?>
                        <div class="alert alert-danger"><?= Html::encode($error) ?></div>
                    <?php endif; ?>

                    <form action="<?= $apiContactoUrl ?>" method="POST" class="contact-form" id="contact-form-html" novalidate>
                        <div class="form-group">
                            <label for="name">Nombre o institución:</label>
                            <input type="text" id="name" name="cor_nombre" class="form-control" required autocomplete="organization">
                        </div>

                        <div class="form-group">
                            <label for="email">Correo electrónico:</label>
                            <input type="email" id="email" name="cor_correo" class="form-control" required autocomplete="email">
                        </div>

                        <div class="form-group">
                            <label for="asunto">Motivo del contacto:</label>
                            <select id="asunto" name="cor_asunto" class="form-control" required>
                                <option value="">Selecciona un motivo</option>
                                <?php foreach($asuntos as $a): ?>
                                    <option value="<?= Html::encode($a->asu_nombre ?? '') ?>">
                                        <?= Html::encode($a->asu_nombre ?? '') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="message">Mensaje:</label>
                            <textarea id="message" name="cor_mensaje" rows="6" class="form-control" required placeholder="Describe tu interés o colaboración con EcoLens."></textarea>
                        </div>

                        <button type="submit" class="cta-button btn" style="background-color:#173b35; color:white; border:none; padding:10px 20px; border-radius:5px;">
                            Enviar mensaje
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>