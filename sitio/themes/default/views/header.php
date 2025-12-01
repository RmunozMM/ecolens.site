<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \stdClass $contenido */
/** @var \stdClass $media */
/** @var string $base */

$contenido = Yii::$app->view->params['contenido'] ?? (object)[];
$paginas   = $contenido->paginas ?? [];

/* ===========================
   Sesión observador + whoami
   =========================== */
$observadorId     = Yii::$app->session->get('observador_id');
$observadorNombre = Yii::$app->session->get('observador_nombre');
$estaObservador   = !empty($observadorId);

/* Carga entorno para ubicar whoami (sin redefinir constantes) */
$envPath = Yii::getAlias('@app') . '/config/ecolens_env.php';
$env     = file_exists($envPath) ? require $envPath : [];
$whoami  = $env['endpoints']['whoami'] ?? null;

/* Si NO hay sesión, confirmamos con whoami usando el token de cookie si existe */
if (!$estaObservador && $whoami && function_exists('curl_init')) {
    // Acepta nombres típicos de tu cookie
    $token = $_COOKIE['observador_token'] ?? $_COOKIE['token'] ?? null;

    if ($token) {
        $ch = curl_init($whoami);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT_MS     => 700,                 // corto para no colgar el header
            CURLOPT_HTTPHEADER     => ["Authorization: Bearer {$token}"],
        ]);
        $raw  = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code === 200 && $raw) {
            $me = json_decode($raw, true);
            $id = $me['id'] ?? $me['observador_id'] ?? null;
            if ($id) {
                $observadorId = $id;
                $observadorNombre = $me['nombre'] ?? $me['observador_nombre'] ?? '';
                Yii::$app->session->set('observador_id', $observadorId);
                Yii::$app->session->set('observador_nombre', $observadorNombre);
                $estaObservador = true;
            }
        }
    }
}
?>

<header class="main-header">
  <div class="logo">
    <a href="<?= $base . "/inicio" ?>">
      <img src="<?= $media->logo_ecolens->url ?? ($base . '/themes/default/assets/img/logo.png') ?>" alt="EcoLens" />
    </a>
  </div>

  <button class="nav-toggle" aria-label="Abrir menú" aria-expanded="false">
    <span class="bar"></span><span class="bar"></span><span class="bar"></span>
  </button>

  <nav class="main-nav">
    <ul class="nav-list">
      <?php foreach ($paginas as $p): ?>
        <?php
          $get = function($obj, $k, $def=null){ return is_object($obj)?($obj->$k??$def):(is_array($obj)?($obj[$k]??$def):$def); };

          $estado   = strtolower(trim((string)$get($p,'pag_estado','')));
          $acceso   = strtolower(trim((string)$get($p,'pag_acceso','publica')));
          $mostrar  = strtoupper(trim((string)$get($p,'pag_mostrar_menu','NO'))) === 'SI';
          $esSec    = strtoupper(trim((string)$get($p,'pag_mostrar_menu_secundario','NO'))) === 'SI';
          $slug     = (string)$get($p,'pag_slug','');
          $label    = (string)$get($p,'pag_label','');
          $titulo   = (string)$get($p,'pag_titulo','');

          // 1) Debe estar publicado
          if ($estado !== 'publicado') continue;
          // 2) Solo menú principal
          if (!$mostrar || $esSec) continue;
          // 3) Acceso privado requiere observador válido
          if ($acceso === 'privada' && !$estaObservador) continue;

          $isActive = Yii::$app->request->get('slug') === $slug;
        ?>
        <li class="nav-item" data-acceso="<?= Html::encode($acceso) ?>">
          <?= Html::a(
            Html::encode($label !== '' ? $label : $titulo),
            ['site/pagina', 'slug' => $slug],
            ['class' => 'nav-link' . ($isActive ? ' active' : '')]
          ) ?>
        </li>
      <?php endforeach; ?>

      <li class="nav-item user-area">
        <?php if ($estaObservador): ?>
          <div class="user-menu">
            <span class="user-name">👋 Hola, <?= Html::encode($observadorNombre) ?></span>
            <div class="user-dropdown">
              <a href="<?= $base ?>/mi-perfil" class="dropdown-item"><i class="fa fa-user-circle"></i> Mi perfil</a>
              <a href="#" id="logout-link" class="dropdown-item logout"><i class="fa fa-sign-out-alt"></i> Cerrar sesión</a>
            </div>
          </div>
        <?php else: ?>
          <a href="<?= $base ?>/login" class="nav-link login-link"><i class="fa fa-sign-in-alt"></i> Iniciar sesión</a>
        <?php endif; ?>
      </li>
    </ul>
  </nav>
</header>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const logoutLink = document.getElementById("logout-link");
  if (!logoutLink) return;

  const base      = "<?= $base ?>";
  const siteLogout = "<?= Url::to(['site/logout']) ?>";
  const endpoints = <?= json_encode($env['endpoints'] ?? []) ?>;
  const apiLogout = endpoints.logout || null;

  logoutLink.addEventListener("click", async (e) => {
    e.preventDefault();

    // 1) Cierra sesión en el API (autoridad)
    try {
      if (apiLogout) {
        await fetch(apiLogout, { method: "POST", credentials: "include" });
      }
    } catch (_) { /* me da igual si falla, seguimos */ }

    // 2) Limpia la sesión del sitio (solo variables propias)
    try {
      await fetch(siteLogout, { method: "POST", credentials: "same-origin" });
    } catch (_) {}

    // 3) Limpia residuos del front y redirige
    try {
      localStorage.removeItem("observador_id");
      localStorage.removeItem("observador_nombre");
    } catch (_) {}
    window.location.href = `${base}/inicio`;
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.querySelector(".nav-toggle");
  const nav    = document.querySelector(".main-nav");

  if (!toggle || !nav) return;

  toggle.addEventListener("click", () => {
    const isActive = nav.classList.toggle("active");
    toggle.setAttribute("aria-expanded", isActive ? "true" : "false");
  });
});
</script>

<style>
.main-nav .nav-list{display:flex;align-items:center;gap:16px}
.user-area{position:relative;display:flex;align-items:center}
.user-menu{display:flex;align-items:center;cursor:pointer}
.user-name{color:#1a1f1c;font-weight:600;padding:0 12px;line-height:1;border-radius:6px;transition:color .2s,background .2s}
.user-name:hover{background:#e8f5ef;color:#2c7a5b}
.user-dropdown{display:none;position:absolute;top:100%;right:0;min-width:180px;background:#fff;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,.15);overflow:hidden;z-index:999}
@media(hover:hover){.user-menu:hover .user-dropdown{display:block}}
.dropdown-item{display:block;padding:10px 14px;color:#333;text-decoration:none;transition:background .2s,color .2s}
.dropdown-item:hover{background:#45AD82;color:#fff}
.logout{border-top:1px solid #e5e5e5}
.nav-toggle{display:none;flex-direction:column;justify-content:space-between;width:28px;height:20px;background:none;border:none;cursor:pointer;padding:0}
.nav-toggle .bar{height:3px;width:100%;background-color:#333;border-radius:2px;transition:all .3s ease}
@media(max-width:768px){
  .nav-toggle{display:flex}
  .main-nav{display:none;flex-direction:column;background:#fff;position:absolute;top:70px;right:0;left:0;z-index:999;padding:1rem 0;box-shadow:0 4px 10px rgba(0,0,0,.1);max-height: 0; overflow: hidden; transition: max-height 0.4s ease-in-out;}
  .main-nav.active{display:flex;     max-height: 500px; /* ajusta según el número de items */}
  .nav-list{flex-direction:column;align-items:center;gap:10px}
}
</style>
