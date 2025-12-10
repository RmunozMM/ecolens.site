<?php
use yii\helpers\Html;

/**
 * Vista: Recuperar contraseña (sitio)
 * Flujo:
 *  - SIN token (?t=...): formulario para pedir enlace (correo/usuario)
 *  - CON token (?t=...): formulario para definir nueva contraseña
 */

/** @var string|null $token */
$token = $token ?? ($_GET['t'] ?? null);
$this->title = 'Recuperar contraseña';

// Cargador simple de entorno (mismo estilo que en login.php)
function ecolens_env_load(): array {
    $candidatos = [
        Yii::getAlias('@app') . '/config/ecolens_env.php',
        dirname(Yii::getAlias('@app')) . '/panel-admin/config/ecolens_env.php',
    ];
    foreach ($candidatos as $p) {
        if (is_file($p)) {
            $env = require $p;
            if (is_array($env)) {
                return $env;
            }
        }
    }

    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $isLocal = (bool)preg_match('/^(localhost|127\.0\.0\.1)(:\d+)?$/i', $host);
    $prefix  = $isLocal ? '/ecolens.site' : '';

    return [
        'isLocal'   => $isLocal,
        'API_BASE'  => ($isLocal ? 'http://localhost:8888' : 'https://ecolens.site') . $prefix . '/panel-admin/web',
        'SITE_BASE' => ($isLocal ? 'http://localhost:8888' : 'https://ecolens.site') . $prefix . '/sitio/web',
        'endpoints' => [],
    ];
}

$env      = ecolens_env_load();
$API_BASE = rtrim($env['API_BASE'], '/');
$SITE_BASE = rtrim($env['SITE_BASE'], '/');

// Endpoints del API para recuperación
$URL_SOLICITAR = $API_BASE . '/api/observador/recuperar-clave-solicitar';
$URL_CONFIRMAR = $API_BASE . '/api/observador/recuperar-clave-confirmar';
$LOGIN_URL     = $SITE_BASE . '/login';
?>
<main class="recover-section container" style="min-height:70vh;display:flex;align-items:center;justify-content:center;">
  <div class="recover-card" style="max-width:520px;width:100%;background:#ffffff;border-radius:12px;box-shadow:0 12px 40px rgba(15,23,42,0.12);padding:32px 28px;">
    <?php if (empty($token)): ?>
      <h1 style="font-size:1.8rem;margin-bottom:.75rem;">Recuperar contraseña</h1>
      <p style="color:#4b5563;margin-bottom:1.5rem;">
        Ingresa tu correo electrónico o nombre de usuario. Te enviaremos un enlace para restablecer tu contraseña.
      </p>

      <form id="form-solicitar">
        <div class="form-group" style="margin-bottom:1rem;">
          <label for="username" style="display:block;font-weight:600;margin-bottom:.35rem;">Correo o nombre de usuario</label>
          <input
            type="text"
            id="username"
            name="username"
            class="form-control"
            placeholder="tu.correo@ejemplo.cl o tu_usuario"
            required
            style="width:100%;padding:.55rem .75rem;border-radius:.5rem;border:1px solid #d1d5db;"
          >
        </div>

        <button
          type="submit"
          id="btn-solicitar"
          class="cta-button"
          style="width:100%;padding:.65rem 1rem;border-radius:.75rem;border:none;background:#45ad82;color:#fff;font-weight:600;cursor:pointer;"
        >
          Enviar enlace de recuperación
        </button>

        <p style="text-align:center;margin-top:1rem;font-size:.9rem;">
          <a href="<?= Html::encode($LOGIN_URL) ?>">Volver al inicio de sesión</a>
        </p>

        <p id="msg-solicitar" style="margin-top:1rem;font-size:.9rem;"></p>
      </form>
    <?php else: ?>
      <h1 style="font-size:1.8rem;margin-bottom:.75rem;">Definir nueva contraseña</h1>
      <p style="color:#4b5563;margin-bottom:1.5rem;">
        Ingresa tu nueva contraseña. Debe tener al menos 8 caracteres.
      </p>

      <form id="form-confirmar">
        <input type="hidden" id="token" value="<?= Html::encode($token) ?>">

        <div class="form-group" style="margin-bottom:1rem;">
          <label for="password" style="display:block;font-weight:600;margin-bottom:.35rem;">Nueva contraseña</label>
          <input
            type="password"
            id="password"
            name="password"
            class="form-control"
            required
            minlength="8"
            style="width:100%;padding:.55rem .75rem;border-radius:.5rem;border:1px solid #d1d5db;"
          >
        </div>

        <div class="form-group" style="margin-bottom:1rem;">
          <label for="password_confirm" style="display:block;font-weight:600;margin-bottom:.35rem;">Confirma la contraseña</label>
          <input
            type="password"
            id="password_confirm"
            name="password_confirm"
            class="form-control"
            required
            minlength="8"
            style="width:100%;padding:.55rem .75rem;border-radius:.5rem;border:1px solid #d1d5db;"
          >
        </div>

        <button
          type="submit"
          id="btn-confirmar"
          class="cta-button"
          style="width:100%;padding:.65rem 1rem;border-radius:.75rem;border:none;background:#45ad82;color:#fff;font-weight:600;cursor:pointer;"
        >
          Guardar nueva contraseña
        </button>

        <p style="text-align:center;margin-top:1rem;font-size:.9rem;">
          <a href="<?= Html::encode($LOGIN_URL) ?>">Volver al inicio de sesión</a>
        </p>

        <p id="msg-confirmar" style="margin-top:1rem;font-size:.9rem;"></p>
      </form>
    <?php endif; ?>
  </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const urlSolicitar = <?= json_encode($URL_SOLICITAR, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) ?>;
  const urlConfirmar = <?= json_encode($URL_CONFIRMAR, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) ?>;

  const formSolicitar = document.getElementById('form-solicitar');
  if (formSolicitar) {
    formSolicitar.addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn  = document.getElementById('btn-solicitar');
      const msg  = document.getElementById('msg-solicitar');
      const user = document.getElementById('username').value.trim();

      if (!user) {
        msg.textContent = 'Debes ingresar tu correo o usuario.';
        msg.style.color = '#b91c1c';
        return;
      }

      btn.disabled = true;
      btn.textContent = 'Enviando...';
      msg.textContent = '';

      try {
        const resp = await fetch(urlSolicitar, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username: user })
        });
        const data = await resp.json().catch(() => ({}));

        msg.textContent = data.message || 'Solicitud procesada.';
        msg.style.color = data.success ? '#15803d' : '#b91c1c';
      } catch (err) {
        console.error(err);
        msg.textContent = 'No se pudo contactar al servidor.';
        msg.style.color = '#b91c1c';
      } finally {
        btn.disabled = false;
        btn.textContent = 'Enviar enlace de recuperación';
      }
    });
  }

  const formConfirmar = document.getElementById('form-confirmar');
  if (formConfirmar) {
    formConfirmar.addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn   = document.getElementById('btn-confirmar');
      const msg   = document.getElementById('msg-confirmar');
      const token = document.getElementById('token').value.trim();
      const pass1 = document.getElementById('password').value.trim();
      const pass2 = document.getElementById('password_confirm').value.trim();

      if (!pass1 || !pass2) {
        msg.textContent = 'Debes completar ambos campos.';
        msg.style.color = '#b91c1c';
        return;
      }
      if (pass1 !== pass2) {
        msg.textContent = 'Las contraseñas no coinciden.';
        msg.style.color = '#b91c1c';
        return;
      }

      btn.disabled = true;
      btn.textContent = 'Guardando...';
      msg.textContent = '';

      try {
        const resp = await fetch(urlConfirmar, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            token: token,
            password: pass1,
            password_confirm: pass2
          })
        });
        const data = await resp.json().catch(() => ({}));

        msg.textContent = data.message || 'Solicitud procesada.';
        msg.style.color = data.success ? '#15803d' : '#b91c1c';
      } catch (err) {
        console.error(err);
        msg.textContent = 'No se pudo contactar al servidor.';
        msg.style.color = '#b91c1c';
      } finally {
        btn.disabled = false;
        btn.textContent = 'Guardar nueva contraseña';
      }
    });
  }
});
</script>
