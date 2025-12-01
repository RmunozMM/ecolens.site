<?php
use yii\helpers\Html;
use yii\helpers\Markdown;
use app\helpers\SitioUtilidades;

/** @var object $pagina */

// Define título de la página dinámicamente (para el <title>)
if (!empty($pagina->pag_titulo)) {
    $this->title = $pagina->pag_titulo;
}

$contenidoBruto = $pagina->pag_contenido_programador ?? '';
$contenidoProcesado = SitioUtilidades::fixContentUrls(
    SitioUtilidades::procesarLinksDinamicos($contenidoBruto)
);

// Detecta si contiene etiquetas HTML o solo texto/Markdown
if (strip_tags($contenidoProcesado) !== $contenidoProcesado) {
    // Contiene HTML → se imprime tal cual
    $contenidoFinal = $contenidoProcesado;
} else {
    // Contiene texto plano o Markdown → convierte a HTML (GitHub-Flavored Markdown)
    $contenidoFinal = Markdown::process($contenidoProcesado, 'gfm');
}
?>
<article class="pagina-generica container">
  <div class="page-content">
    <?= $contenidoFinal ?>
  </div>
</article>