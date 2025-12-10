<?php
// Vista: login (sitio)
use yii\helpers\Url;

$this->title = "Iniciar Sesión en EcoLens";

/**
 * Cargador robusto de entorno.
 * - Busca /config/ecolens_env.php en el app actual (sitio) y en panel-admin.
 * - Si no existe, usa defaults para local/prod.
 */
function ecolens_env_load(): array {
    $candidatos = [
        Yii::getAlias('@app') . '/config/ecolens_env.php',                 // sitio/config
        dirname(Yii::getAlias('@app')) . '/panel-admin/config/ecolens_env.php', // panel-admin/config
    ];
    foreach ($candidatos as $p) {
        if (is_file($p)) {
            define('ECO_ENV_INCLUDED', true);
            /** @var array $env */
            $env = require $p;
            if (is_array($env)) return $env;
        }
    }

    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $isLocal = (bool)preg_match('/^(localhost|127\.0\.0\.1)(:\d+)?$/i', $host);
    $prefix = $isLocal ? '/ecolens.site' : '';
    return [
        'isLocal'   => $isLocal,
        'API_BASE'  => ($isLocal ? 'http://localhost:8888' : 'https://ecolens.site') . $prefix . '/panel-admin/web',
        'SITE_BASE' => ($isLocal ? 'http://localhost:8888' : 'https://ecolens.site') . $prefix . '/sitio/web',
        'endpoints' => []
    ];
}

$env = ecolens_env_load();
$API_BASE  = rtrim($env['API_BASE'], '/');
$SITE_BASE = rtrim($env['SITE_BASE'], '/');

$LOGIN_URL = $env['endpoints']['login']   ?? ($API_BASE . '/api/observador/login');
$REDIRECT  = $SITE_BASE . '/detectar';
$REGISTER  = $SITE_BASE . '/registro';
$RECOVER   = Url::to(['/recuperar-clave'], true);
?>
<main>
  <section class="login-section container">
    <h1>Iniciar Sesión en EcoLens</h1>
    <p>Accede a tu panel de monitoreo y a tus detecciones guardadas.</p>

    <form id="login-form" class="contact-form">
      <div class="form-group">
        <label for="username">Correo electrónico:</label>
        <input type="email" id="username" name="username" required placeholder="tu.correo@ejemplo.cl" />
      </div>

      <div class="form-group">
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required placeholder="••••••••" />
      </div>

      <button type="submit" id="login-button" class="cta-button">Acceder</button>

      <p class="register-note" style="margin-top:1.2rem;">
        ¿No tienes cuenta?
        <a href="<?= htmlspecialchars($REGISTER, ENT_QUOTES, 'UTF-8') ?>">Regístrate aquí</a>
      </p>

      <p class="register-note" style="margin-top:0.4rem;font-size:0.9rem;">
        ¿Olvidaste tu contraseña?
        <a href="<?= htmlspecialchars($RECOVER, ENT_QUOTES, 'UTF-8') ?>">Recupérala aquí</a>
      </p>
    </form>
  </section>
</main>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("login-form");
  if (!form) return;

  const apiUrl      = <?= json_encode($LOGIN_URL, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;
  const redirectUrl = <?= json_encode($REDIRECT,   JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;

  console.log("🔗 Intentando login hacia:", apiUrl);

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const email    = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();
    if (!email || !password) {
      alert("Por favor ingresa tu correo y contraseña.");
      return;
    }

    try {
      const resp = await fetch(apiUrl, {
        method: "POST",
        mode: "cors",
        headers: { "Content-Type": "application/json" },
        credentials: "include",
        body: JSON.stringify({ username: email, password })
      });

      if (!resp.ok) {
        console.error("HTTP error:", resp.status, resp.statusText);
        alert("Error del servidor: " + resp.status);
        return;
      }

      const text = await resp.text();
      let data;
      try {
        data = JSON.parse(text);
      } catch (parseErr) {
        console.error("Respuesta no JSON:", text);
        alert("Respuesta inesperada del servidor. Ver consola.");
        return;
      }

      console.log("📦 Respuesta del API:", data);

      if (data && data.success) {
        try {
          localStorage.setItem("observador_id", String(data.id ?? ""));
          localStorage.setItem("observador_nombre", String(data.nombre ?? ""));
          localStorage.setItem("observador_email", String(data.email ?? ""));
        } catch (storageErr) {
          console.warn("No se pudo guardar en localStorage:", storageErr);
        }
        window.location.href = redirectUrl;
      } else {
        alert(data?.message || "Credenciales inválidas.");
      }
    } catch (err) {
      console.error("❌ Error de conexión o CORS:", err);
      alert("No se pudo contactar al servidor. Ver consola.");
    }
  });
});
</script>
