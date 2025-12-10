<?php
use yii\helpers\Html;
use yii\helpers\Url;

// Variables globales
$year    = date('Y');
$cliente = Html::encode($opciones->cliente_nombre ?? Yii::$app->name);
$autor   = Html::encode($opciones->meta_author ?? 'Cápsula Tech');

// Normalizamos listas
$paginasSec = (array)($contenido->paginas_secundarias ?? []);
$redes      = (array)($contenido->redesSociales ?? []);

// Helper simple para extraer valores de objetos o arrays
$get = function($item, $key, $default = null) {
    if (is_object($item)) return $item->$key ?? $default;
    if (is_array($item)) return $item[$key] ?? $default;
    return $default;
};
?>

<footer class="main-footer">
  <div class="footer-content">
    <div class="footer-top-row">

      <!-- 🔹 Menú secundario -->
      <div class="footer-column">
        <h4>Menú Secundario</h4>
        <div class="footer-links">
          <?php
          $enlaces = [];
          foreach ($paginasSec as $p) {
              $visible = strtoupper(trim((string)$get($p, 'pag_mostrar_menu_secundario', 'NO'))) === 'SI';
              $slug    = trim((string)$get($p, 'pag_slug', ''));
              $titulo  = trim((string)$get($p, 'pag_titulo', ''));

              if ($visible && $slug !== '') {
                  $enlaces[] = Html::a(Html::encode($titulo), ['site/pagina', 'slug' => $slug], ['class' => 'footer-link']);
              }
          }

          if (!empty($enlaces)) {
              echo implode('<span class="divider"> | </span>', $enlaces);
          } else {
              echo '<span class="footer-link text-muted">Sin páginas secundarias</span>';
          }
          ?>
        </div>
      </div>

      <!-- 🔹 Redes sociales -->
      <div class="footer-column">
        <h4>Síguenos en nuestras redes</h4>
        <div class="footer-social">
          <?php
            if (!empty($redes)) {
                foreach ($redes as $r) {
                    $publicada = strtoupper(trim((string)$get($r, 'red_publicada', 'NO'))) === 'SI';
                    $enlace    = trim((string)$get($r, 'red_enlace', '#'));
                    $perfil    = trim((string)$get($r, 'red_perfil', ''));
                    $nombre    = trim((string)$get($r, 'red_nombre', ''));
                    $iconoRaw  = trim((string)$get($r, 'red_icono', ''));

                    // 🔹 Separar ícono y color según tu formato "fab fa-facebook|#1877F2"
                    [$iconClass, $color] = array_pad(explode('|', $iconoRaw, 2), 2, '');
                    $iconClass = trim($iconClass ?: 'fa fa-circle');
                    $color     = trim(str_replace('#', '', $color)) ?: 'ffffff';

                    if ($publicada && $enlace) {
                        echo Html::a(
                            "<i class='$iconClass' style='color:#$color'></i>",
                            (strpos($enlace, 'http') === 0 ? $enlace : 'https://' . $enlace . '/' . $perfil),
                            [
                                'class'  => 'social-icon',
                                'title'  => Html::encode($nombre),
                                'target' => '_blank',
                                'rel'    => 'noopener',
                            ]
                        );
                    }
                }
            } else {
                echo '<span class="footer-link text-muted">Sin redes disponibles</span>';
            }
            ?>
          
        </div>
      </div>
    </div>

    <!-- 🔹 Línea inferior -->
    <div class="footer-bottom">
      <p>&copy; <?= $year ?> <strong><?= $cliente ?></strong>. Todos los derechos reservados.</p>
      <p>Autor: <?= $autor ?></p>
    </div>
  </div>
</footer>

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

<style>
.main-footer {
  background: #173b35;
  color: #fff;
  font-family: "Nunito Sans", sans-serif;
  padding: 2.5rem 1rem;
  text-align: center;
}
.footer-top-row {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 3rem;
  margin-bottom: 1.5rem;
}
.footer-column h4 {
  font-weight: 700;
  font-size: 1.1rem;
  border-bottom: 2px solid #5fc59d;
  display: inline-block;
  padding-bottom: 4px;
  margin-bottom: 0.8rem;
}
.footer-links {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: .4rem;
  font-size: .95rem;
}
.footer-link {
  color: #cfe6da;
  text-decoration: none;
  transition: color .2s;
}
.footer-link:hover {
  color: #fff;
  text-decoration: underline;
}
.divider {
  color: #6ebea0;
}
.footer-social {
  display: flex;
  justify-content: center;
  gap: 1.2rem;
  font-size: 1.4rem;
}
.social-icon {
  text-decoration: none;
  transition: transform .2s ease;
}
.social-icon:hover {
  transform: scale(1.2);
}
.footer-bottom {
  border-top: 1px solid rgba(255,255,255,0.15);
  padding-top: 1.2rem;
  font-size: .9rem;
  color: #d9f0e4;
}
@media (max-width: 768px) {
  .footer-top-row {
    flex-direction: column;
    align-items: center;
    gap: 2rem;
  }
}
</style>