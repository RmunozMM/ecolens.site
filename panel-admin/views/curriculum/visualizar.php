<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\helpers\LibreriaHelper;

/* @var $this yii\web\View */
/* @var $curriculum array */
/* @var $perfil array */
/* @var $habilidades array */
/* @var $herramientas array */
/* @var $formaciones array */
/* @var $cursos array */
/* @var $certificaciones array */
/* @var $experiencias array */

$this->title = 'Curriculum Vitae'; 
?>

<div class="container-cv">
    <div class="btn-container">
        <a href="<?= Url::to(['curriculum/descargar-pdf']) ?>" class="btn-download btn-pdf">
            <i class="fas fa-file-pdf"></i> Descargar PDF
        </a>
        
        <a href="<?= Url::to(['curriculum/descargar-word']) ?>" class="btn-download btn-word">
            <i class="fas fa-file-word"></i> Descargar Word
        </a>

        <a href="<?= Url::to(['curriculum/descargar-latex']) ?>" class="btn-download btn-latex">
            <i class="fas fa-file-code"></i> Descargar LaTeX
        </a>
    </div>

    <div class="pdf-preview"> 
        <div class="pdf-page">

            <div class="personal-info header-resume">
                
                <h1 class="cv-nombre"><?= Html::encode($perfil["per_nombre"] ?? '') ?></h1>
                <p class="cv-titulo"><?= Html::encode($curriculum["cur_titulo"] ?? '') ?></p>
                <p class="cv-subtitulo"><?= Html::encode($curriculum["cur_subtitulo"] ?? '') ?></p>
                <p class="cv-educacion"><?= Html::encode($curriculum["cur_casa_estudio"] ?? '') ?></p>
                
                <p class="cv-contacto">
                    <a class="cv-link" href="tel:<?= Html::encode($perfil["per_telefono"] ?? '') ?>">
                        <?= Html::encode($perfil["per_telefono"] ?? '') ?>
                    </a> – 
                    <a class="cv-link" href="mailto:<?= Html::encode($perfil["per_correo"] ?? '') ?>">
                        <?= Html::encode($perfil["per_correo"] ?? '') ?>
                    </a>
                </p>
                <p class="cv-redes">
                    <?php if (!empty($perfil["per_linkedin"])): ?>
                        <a class="cv-link" href="<?= Html::encode($perfil["per_linkedin"]) ?>" target="_blank">
                            Linkedin.com/<?= Html::encode(basename($perfil["per_linkedin"])) ?>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($perfil["per_linkedin"]) && !empty($perfil["per_sitio_web"])): ?>
                         – 
                    <?php endif; ?>
                    <?php if (!empty($perfil["per_sitio_web"])): ?>
                        <a class="cv-link" href="<?= Html::encode($perfil["per_sitio_web"]) ?>" target="_blank">
                            <?= Html::encode(preg_replace('#^https?://#', '', $perfil["per_sitio_web"])) ?>
                        </a>
                    <?php endif; ?>
                </p>
            </div>

            <?php if(!empty($curriculum["cur_resumen_profesional"])): ?>
                <h2>Resumen Profesional</h2>
                <div><?= Html::decode($curriculum["cur_resumen_profesional"]) ?></div>
            <?php endif; ?>

            <?php if (!empty($experiencias)): ?>
                <h2>Experiencia Profesional</h2>
                <?php foreach ($experiencias as $experiencia): ?>
                    <div class="experiencia-item">
                        <h3>
                            <?= Html::encode($experiencia["exp_cargo"] ?? '') ?> - <?= Html::encode($experiencia["exp_empresa"] ?? '') ?>
                        </h3>
                        <?php
                        $fechaInicioFormateada = "N/A";
                        if (!empty($experiencia["exp_fecha_inicio"])) {
                            $fechaInicio = strtotime($experiencia["exp_fecha_inicio"]);
                            if ($fechaInicio !== false) {
                                $anioInicio = date('Y', $fechaInicio);
                                $mesInicioIngles = date('F', $fechaInicio);
                                $mesInicioEspanol = LibreriaHelper::obtenerNombreMes($mesInicioIngles);
                                $fechaInicioFormateada = $mesInicioEspanol . ' ' . $anioInicio;
                            }
                        }
                        $fechaFinFormateada = "Actualidad";
                        if (!empty($experiencia["exp_fecha_fin"])) {
                            $fechaFin = strtotime($experiencia["exp_fecha_fin"]);
                            if ($fechaFin !== false) {
                                $anioFin = date('Y', $fechaFin);
                                $mesFinIngles = date('F', $fechaFin);
                                $mesFinEspanol = LibreriaHelper::obtenerNombreMes($mesFinIngles);
                                $fechaFinFormateada = $mesFinEspanol . ' ' . $anioFin;
                            }
                        }
                        ?>
                        <?php if ($fechaInicioFormateada !== "N/A"): ?>
                        <p><strong>Período:</strong> <?= Html::encode($fechaInicioFormateada) ?> - <?= Html::encode($fechaFinFormateada) ?></p>
                        <?php endif; ?>

                        <div><?= Html::decode($experiencia["exp_descripcion"] ?? '') ?></div>
                        <hr>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($formaciones)): ?>
                <h2>Formación Académica</h2>
                <div class="formacion-academica">
                    <?php foreach ($formaciones as $formacion): ?>
                        <?php
                        $fechaInicioFormateada = "N/A";
                        if (!empty($formacion["for_fecha_inicio"])) {
                            $fechaInicio = strtotime($formacion["for_fecha_inicio"]);
                            if ($fechaInicio !== false) {
                                $anioInicio = date('Y', $fechaInicio);
                                $mesInicioEn = date('F', $fechaInicio);
                                $mesInicioEs = LibreriaHelper::obtenerNombreMes($mesInicioEn);
                                $fechaInicioFormateada = $mesInicioEs . ' ' . $anioInicio;
                            }
                        }
                        $fechaFinFormateada = "Actualidad";
                        if (!empty($formacion["for_fecha_fin"])) {
                            $fechaFin = strtotime($formacion["for_fecha_fin"]);
                            if ($fechaFin !== false) {
                                $anioFin = date('Y', $fechaFin);
                                $mesFinEn = date('F', $fechaFin);
                                $mesFinEs = LibreriaHelper::obtenerNombreMes($mesFinEn);
                                $fechaFinFormateada = $mesFinEs . ' ' . $anioFin;
                            }
                        }
                        ?>
                        <div class="formacion-entry">
                            <p>
                                <strong><?= Html::encode($formacion["for_institucion"] ?? '') ?></strong><br>
                                <?= Html::encode($formacion["for_grado_titulo"] ?? '') ?><br>
                                <?php if ($fechaInicioFormateada !== "N/A"): ?>
                                    <em><?= Html::encode($fechaInicioFormateada) ?> - <?= Html::encode($fechaFinFormateada) ?></em>
                                <?php endif; ?>
                            </p>
                        </div>
                        <hr>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($certificaciones)): ?>
                <h2>Certificaciones</h2>
                <ul>
                    <?php foreach ($certificaciones as $certificacion): ?>
                        <li>
                            <strong><?= Html::encode($certificacion["for_grado_titulo"] ?? '') ?></strong>
                            - <?= Html::encode($certificacion["for_institucion"] ?? '') ?>
                            <?php if (!empty($certificacion["for_fecha_fin"])): ?>
                                (<?= Html::encode(date('Y', strtotime($certificacion["for_fecha_fin"]))) ?>)
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (!empty($cursos)): ?>
                <h2>Cursos</h2>
                <ul>
                    <?php foreach ($cursos as $curso): ?>
                        <li>
                            <strong><?= Html::encode($curso["for_grado_titulo"] ?? '') ?></strong>
                            - <?= Html::encode($curso["for_institucion"] ?? '') ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (!empty($habilidades)): ?>
                <h2>Habilidades</h2>
                <ul>
                    <?php foreach ($habilidades as $habilidad): ?>
                        <li><?= Html::encode($habilidad) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (!empty($herramientas)): ?>
                <h2>Herramientas</h2>
                <ul>
                    <?php foreach ($herramientas as $herramienta): ?>
                        <li><?= Html::encode($herramienta) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (!empty($curriculum["cur_fecha_modificacion"])): ?>
                <?php
                $timestamp = strtotime($curriculum["cur_fecha_modificacion"]);
                if ($timestamp !== false):
                    $mes = date('F', $timestamp);
                    $anio = date('Y', $timestamp);
                    $mesNombre = LibreriaHelper::obtenerNombreMes($mes);
                ?>
                <div class="update-section">
                    <p>Actualización, <?= Html::encode($mesNombre . ' ' . $anio) ?></p>
                </div>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php
$this->registerCssFile(
    Yii::getAlias('@web/css/cv.css'),
    ['depends' => [\yii\web\YiiAsset::class]]
);
?>