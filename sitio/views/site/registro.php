<?php
// Vista: Registro de Observador

$this->title = "Crea tu Cuenta | EcoLens";

/** Carga de entorno (busca en sitio y panel; si no existe, usa defaults). */
function ecolens_env_load(): array {
    $candidatos = [
        Yii::getAlias('@app') . '/config/ecolens_env.php',
        dirname(Yii::getAlias('@app')) . '/panel-admin/config/ecolens_env.php',
    ];
    foreach ($candidatos as $p) {
        if (is_file($p)) {
            define('ECO_ENV_INCLUDED', true);
            $env = require $p;
            if (is_array($env)) return $env;
        }
    }
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $isLocal = (bool)preg_match('/^(localhost|127\.0\.0\.1)(:\d+)?$/i', $host);
    $prefix  = $isLocal ? '/ecolens.site' : '';
    return [
        'isLocal'   => $isLocal,
        'API_BASE'  => ($isLocal ? 'http://localhost:8888' : 'https://ecolens.site') . $prefix . '/panel-admin/web',
        'SITE_BASE' => ($isLocal ? 'http://localhost:8888' : 'https://ecolens.site') . $prefix . '/sitio/web',
        'endpoints' => [],
    ];
}
$env       = ecolens_env_load();
$API_BASE  = rtrim($env['API_BASE'], '/');
$SITE_BASE = rtrim($env['SITE_BASE'], '/');

$API_REGISTRAR = $env['endpoints']['obs_registrar'] ?? ($API_BASE . '/api/observador/registrar');
$LOGIN_URL     = $SITE_BASE . '/login';
?>
<style>
  body{font-family:'Nunito Sans',sans-serif;background:#fafafa;margin:0;padding:0;color:#333}
  .container{max-width:600px;margin:0 auto;padding:2rem 1rem}
  h1{font-family:'Lora',serif;text-align:center;color:#2e7d32}
  p{text-align:center;color:#555}
  .contact-form{background:#fff;padding:2rem;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.08);margin-top:2rem}
  .form-group{margin-bottom:1.2rem}
  label{display:block;font-weight:600;margin-bottom:.3rem}
  input,select{width:100%;padding:.7rem;border:1px solid #ccc;border-radius:6px;font-size:1rem}
  input:focus,select:focus{outline:none;border-color:#2e7d32;box-shadow:0 0 3px rgba(46,125,50,.4)}
  .hint{font-size:.85rem;color:#6b7280;margin-top:.25rem}
  .cta-button{background:#2e7d32;color:#fff;border:none;border-radius:6px;padding:.8rem 1.5rem;font-size:1rem;cursor:pointer;transition:background .2s ease;width:100%}
  .cta-button:hover{background:#1b5e20}
  .register-note{text-align:center;margin-top:1rem}
  .register-note a{color:#2e7d32;text-decoration:none;font-weight:600}
  .register-note a:hover{text-decoration:underline}
  #ciudad-container{display:none}
  .error-box{margin-top:1rem;padding:.8rem;border-radius:8px;background:#fee2e2;color:#7f1d1d;border:1px solid #fecaca;display:none}
  .success-box{margin-top:1rem;padding:.8rem;border-radius:8px;background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;display:none}
  .row-2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
  @media(max-width:640px){.row-2{grid-template-columns:1fr}}
</style>

<section class="login-section container">
  <h1>Crea tu Cuenta en EcoLens</h1>
  <p>Únete a la comunidad de monitoreo de fauna chilena.</p>

  <form id="register-form" class="contact-form" novalidate>
    <div class="form-group">
      <label for="reg-nombre">Nombre Completo:</label>
      <input type="text" id="reg-nombre" name="obs_nombre" autocomplete="name" required />
    </div>

    <div class="form-group">
      <label for="reg-usuario">Nombre de Usuario:</label>
      <input
        type="text"
        id="reg-usuario"
        name="obs_usuario"
        inputmode="latin"
        pattern="[a-z0-9._-]{3,150}"
        minlength="3"
        maxlength="150"
        autocomplete="username"
        required
      />
      <div class="hint">Solo minúsculas, números, punto, guion y guion bajo. Ej: <code>maria.perez_23</code></div>
    </div>

    <div class="form-group">
      <label for="reg-email">Correo Electrónico:</label>
      <input type="email" id="reg-email" name="obs_email" autocomplete="email" required />
    </div>

    <div class="row-2">
      <div class="form-group">
        <label for="reg-institucion">Institución (opcional):</label>
        <input type="text" id="reg-institucion" name="obs_institucion" placeholder="Ej: Universidad Mayor" />
      </div>

      <div class="form-group">
        <label for="reg-experiencia">Nivel de experiencia:</label>
        <select id="reg-experiencia" name="obs_experiencia" required>
          <!-- Debe coincidir con las enum del modelo -->
          <option value="principiante">Principiante</option>
          <option value="aficionado">Aficionado</option>
          <option value="experto">Experto</option>
          <option value="institucional">Institucional</option>
        </select>
      </div>
    </div>

    <div class="row-2">
      <div class="form-group">
        <label for="reg-pais">País:</label>
        <select id="reg-pais" name="obs_pais" required>
          <option value="">Selecciona tu país</option>
          <option value="Chile">Chile</option>
          <option value="Argentina">Argentina</option>
          <option value="Perú">Perú</option>
          <option value="Bolivia">Bolivia</option>
          <option value="Colombia">Colombia</option>
          <option value="México">México</option>
          <option value="España">España</option>
          <option value="Estados Unidos">Estados Unidos</option>
          <option value="Otro">Otro</option>
        </select>
      </div>

      <div class="form-group" id="ciudad-container">
        <label for="reg-ciudad">Región (solo Chile):</label>
        <select id="reg-ciudad" name="obs_ciudad">
          <option value="">Selecciona tu región</option>
          <option value="Arica y Parinacota">Arica y Parinacota</option>
          <option value="Tarapacá">Tarapacá</option>
          <option value="Antofagasta">Antofagasta</option>
          <option value="Atacama">Atacama</option>
          <option value="Coquimbo">Coquimbo</option>
          <option value="Valparaíso">Valparaíso</option>
          <option value="Metropolitana">Metropolitana</option>
          <option value="O’Higgins">O’Higgins</option>
          <option value="Maule">Maule</option>
          <option value="Ñuble">Ñuble</option>
          <option value="Biobío">Biobío</option>
          <option value="La Araucanía">La Araucanía</option>
          <option value="Los Ríos">Los Ríos</option>
          <option value="Los Lagos">Los Lagos</option>
          <option value="Aysén">Aysén</option>
          <option value="Magallanes">Magallanes</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="reg-password">Contraseña:</label>
      <input type="password" id="reg-password" name="password" minlength="8" autocomplete="new-password" required />
      <div class="hint">Mínimo 8 caracteres.</div>
    </div>

    <button type="submit" class="cta-button" id="btn-submit">Registrarme</button>

    <div id="msg-error" class="error-box"></div>
    <div id="msg-ok" class="success-box"></div>

    <p class="register-note">
      ¿Ya tienes cuenta? <a href="<?= htmlspecialchars($LOGIN_URL, ENT_QUOTES, 'UTF-8') ?>">Inicia sesión aquí</a>
    </p>
  </form>
</section>

<script>
(function(){
  const API_REGISTRAR = <?= json_encode($API_REGISTRAR, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;
  const LOGIN_URL     = <?= json_encode($LOGIN_URL,     JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;

  // Mostrar/ocultar regiones cuando el país es Chile
  function toggleRegiones(paisSelectId, regionContainerId) {
    const paisSelect = document.getElementById(paisSelectId);
    const regionContainer = document.getElementById(regionContainerId);
    if (!paisSelect || !regionContainer) return;
    const apply = () => {
      regionContainer.style.display = (paisSelect.value === "Chile") ? "block" : "none";
    };
    paisSelect.addEventListener("change", apply);
    apply();
  }
  toggleRegiones("reg-pais", "ciudad-container");

  // Normalización frontend de obs_usuario
  const usuarioInput = document.getElementById("reg-usuario");
  usuarioInput.addEventListener("input", () => {
    const cur = usuarioInput.value;
    const norm = cur.toLowerCase().replace(/[^a-z0-9._-]+/g, "");
    if (cur !== norm) usuarioInput.value = norm;
  });

  const form     = document.getElementById("register-form");
  const btn      = document.getElementById("btn-submit");
  const msgErr   = document.getElementById("msg-error");
  const msgOk    = document.getElementById("msg-ok");
  const showErr  = (t) => { msgErr.textContent = t; msgErr.style.display = "block"; msgOk.style.display = "none"; };
  const showOk   = (t) => { msgOk.textContent = t; msgOk.style.display  = "block"; msgErr.style.display = "none"; };

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    msgErr.style.display = "none"; msgOk.style.display = "none";

    const nombre  = document.getElementById("reg-nombre").value.trim();
    const usuario = usuarioInput.value.trim();
    const email   = document.getElementById("reg-email").value.trim();
    const instit  = document.getElementById("reg-institucion").value.trim();
    const exp     = document.getElementById("reg-experiencia").value;
    const pais    = document.getElementById("reg-pais").value;
    const ciudad  = document.getElementById("reg-ciudad").value;
    const pass    = document.getElementById("reg-password").value;

    if (!nombre || !usuario || !email || !pais || !pass) {
      showErr("Completa todos los campos obligatorios.");
      return;
    }
    if (!/^[a-z0-9._-]{3,150}$/.test(usuario)) {
      showErr("El usuario debe tener mínimo 3 caracteres y solo minúsculas, números, punto, guion y guion bajo.");
      return;
    }
    if (pass.length < 8) {
      showErr("La contraseña debe tener al menos 8 caracteres.");
      return;
    }

    btn.disabled = true;

    const payload = {
      obs_nombre: nombre,
      obs_usuario: usuario,
      obs_email: email,
      obs_institucion: instit || "",
      // Debe coincidir con las enum del modelo
      obs_experiencia: exp || "principiante",
      obs_pais: pais,
      obs_ciudad: ciudad || "",
      password: pass
    };

    try {
      const resp = await fetch(API_REGISTRAR, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
        credentials: "include"
      });

      const text = await resp.text();
      let data;
      try { data = JSON.parse(text); }
      catch {
        console.error("Respuesta no JSON:", text);
        showErr("Error inesperado del servidor.");
        btn.disabled = false;
        return;
      }

      if (data.success) {
        // Compatibles con el flujo futuro de activación:
        // si el backend retorna flags, los usamos; si no, redirigimos a login.
        if (data.activation_required || data.email_sent) {
          showOk("✅ Registro exitoso. Te enviamos un correo para activar tu cuenta. Revisa tu bandeja de entrada y spam.");
        } else {
          showOk("✅ Registro exitoso. Redirigiendo a inicio de sesión…");
          setTimeout(() => { window.location.href = LOGIN_URL; }, 900);
        }
        form.reset();
      } else {
        let msg = data.message || "No se pudo registrar.";
        if (data.errors && typeof data.errors === "object") {
          const detalles = Object.values(data.errors).flat().join(" · ");
          if (detalles) msg += " " + detalles;
        }
        showErr("⚠️ " + msg);
      }
    } catch (err) {
      console.error(err);
      showErr("🚫 Error de conexión al registrar.");
    } finally {
      btn.disabled = false;
    }
  });
})();
</script>