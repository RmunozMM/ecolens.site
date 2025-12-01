<?php
use yii\helpers\Html;
use yii\helpers\Url;

// Título
$this->title = 'Mi Perfil | EcoLens';

/**
 * Carga entorno compartido (robusto aunque el archivo no exista).
 */
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
    // Fallback sensato para local/prod
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
$env      = ecolens_env_load();
$API_BASE = rtrim($env['API_BASE'], '/');

// Endpoints Observador
$API_ROOT        = $API_BASE . '/api/observador';
$API_DETALLE     = $env['endpoints']['obs_detalle']    ?? ($API_ROOT . '/detalle');
$API_ACTUALIZAR  = $env['endpoints']['obs_actualizar'] ?? ($API_ROOT . '/actualizar');
$API_FOTO        = $env['endpoints']['obs_foto']       ?? ($API_ROOT . '/subir-foto');
$API_CAMBIAR_PASS= $env['endpoints']['obs_cambiar']    ?? ($API_ROOT . '/cambiar-password');
$API_ELIMINAR    = $env['endpoints']['obs_eliminar']   ?? ($API_ROOT . '/eliminar');
$API_WHOAMI      = $env['endpoints']['whoami']         ?? ($API_ROOT . '/whoami');

// Datos de sesión (si existen)
$observadorId     = (int)Yii::$app->session->get('observador_id', 0);
$observadorNombre = Yii::$app->session->get('observador_nombre');
$observadorEmail  = Yii::$app->session->get('observador_email');
$observadorUser   = Yii::$app->session->get('observador_usuario');
?>
<main class="perfil-main container">
  <section class="perfil-section">
    <h1 class="perfil-title">Mi Perfil de Usuario</h1>
    <p class="perfil-subtitle">
      Actualiza tu información personal. Algunos campos son de solo lectura por seguridad.
    </p>

    <!-- Avatar -->
    <div class="perfil-avatar">
      <div class="avatar-circle">
        <img
          src="<?= Url::to('@web/themes/default/assets/img/avatar.png') ?>"
          alt="Foto de Perfil"
          id="avatar-img"
        />
      </div>
      <button class="cta-button secondary-button" id="upload-avatar">
        Cambiar Foto
      </button>
      <input type="file" id="avatar-upload" accept="image/*" hidden />
    </div>

    <!-- Contenido principal -->
    <div class="perfil-content">
      <div class="perfil-card">
        <h2>Información Personal</h2>

        <label for="email">Correo Electrónico:</label>
        <input
          type="email"
          id="email"
          class="perfil-input readonly"
          value="<?= Html::encode($observadorEmail ?? '') ?>"
          readonly
        />

        <label for="nombre">Nombre Completo:</label>
        <input type="text" id="nombre" class="perfil-input" />

        <label for="usuario">Nombre de Usuario:</label>
        <input type="text" id="usuario" class="perfil-input" />

        <label for="institucion">Institución / Afiliación:</label>
        <input type="text" id="institucion" class="perfil-input" placeholder="Ej: Universidad Mayor" />

        <label for="experiencia">Nivel de Experiencia:</label>
        <select id="experiencia" class="perfil-input">
          <option value="principiante">Principiante</option>
          <option value="aficionado">Aficionado</option>
          <option value="avanzado">Avanzado</option>
          <option value="experto">Experto</option>
          <option value="institucional">Institucional</option>
        </select>

        <label for="pais">País:</label>
        <select id="pais" class="perfil-input">
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

        <div class="form-group" id="ciudad-container" style="display:none;">
          <label for="ciudad">Región (solo Chile):</label>
          <select id="ciudad" class="perfil-input">
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

        <button class="cta-button save-button" id="btn-guardar">
          Guardar Cambios
        </button>
      </div>

      <div class="perfil-card">
        <h2>Seguridad</h2>
        <p style="color:#555;font-size:0.95em;margin-bottom:10px;">
          Puedes cambiar tu contraseña o eliminar tu cuenta permanentemente.
        </p>
        <div class="form-inline">
          <input type="password" id="pass-actual" class="perfil-input" placeholder="Contraseña actual" />
          <input type="password" id="pass-nueva" class="perfil-input" placeholder="Nueva contraseña (8+)" />
          <button class="cta-button change-pass" id="btn-cambiar-pass">Cambiar Contraseña</button>
        </div>
        <button class="delete-button" id="btn-eliminar">Eliminar Cuenta</button>
      </div>
    </div>
  </section>
</main>

<script>
document.addEventListener("DOMContentLoaded", async () => {
  const API = {
    detalle:  <?= json_encode($API_DETALLE,     JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>,
    actualizar: <?= json_encode($API_ACTUALIZAR,  JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>,
    foto:     <?= json_encode($API_FOTO,        JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>,
    cambiar:  <?= json_encode($API_CAMBIAR_PASS, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>,
    eliminar: <?= json_encode($API_ELIMINAR,     JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>,
    whoami:   <?= json_encode($API_WHOAMI,       JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>,
  };

  let observadorId = <?= (int)$observadorId ?>;
  const $ = (id) => document.getElementById(id);

  // Si no viene la sesión, intenta whoami
  async function ensureObserverId() {
    if (observadorId) return observadorId;
    try {
      const r = await fetch(API.whoami, { credentials: "include" });
      const t = await r.text(); const j = JSON.parse(t);
      if (j && j.authenticated && j.id) observadorId = j.id;
    } catch(_) {}
    return observadorId;
  }

  // ─────────────────────────────────────────────
  // CARGAR PERFIL
  // ─────────────────────────────────────────────
  async function cargarPerfil() {
    const uid = await ensureObserverId();
    if (!uid) { alert("No hay sesión activa."); return; }

    try {
      const res = await fetch(`${API.detalle}?id=${uid}`, { credentials: "include" });
      const txt = await res.text();
      const json = JSON.parse(txt);
      if (!json.success || !json.data) return;

      const o = json.data;
      $("email").value      = o.obs_email ?? "";
      $("nombre").value     = o.obs_nombre ?? "";
      $("usuario").value    = o.obs_usuario ?? "";
      $("institucion").value= o.obs_institucion ?? "";
      $("experiencia").value= o.obs_experiencia ?? "principiante";
      $("pais").value       = o.obs_pais ?? "";
      $("ciudad").value     = o.obs_ciudad ?? "";

      const ciudadContainer = $("ciudad-container");
      if ((o.obs_pais ?? "").toLowerCase() === "chile") ciudadContainer.style.display = "block";
      else ciudadContainer.style.display = "none";

      if (o.obs_foto && String(o.obs_foto).trim() !== "") {
        $("avatar-img").src = o.obs_foto;
      }
    } catch (err) {
      console.error("❌ Error cargando perfil:", err);
    }
  }

  await cargarPerfil();

  // Mostrar/ocultar Región
  $("pais").addEventListener("change", () => {
    const cont = $("ciudad-container");
    if (($("pais").value || "").toLowerCase() === "chile") cont.style.display = "block";
    else { cont.style.display = "none"; $("ciudad").value = ""; }
  });

  // ─────────────────────────────────────────────
  // GUARDAR CAMBIOS
  // ─────────────────────────────────────────────
  $("btn-guardar").addEventListener("click", async () => {
    const uid = await ensureObserverId();
    if (!uid) { alert("No hay sesión activa."); return; }

    const data = {
      obs_nombre: $("nombre").value.trim(),
      obs_usuario: $("usuario").value.trim(),
      obs_institucion: $("institucion").value.trim(),
      obs_experiencia: $("experiencia").value,
      obs_pais: $("pais").value,
      obs_ciudad: $("ciudad").value
    };

    if (!data.obs_nombre || !data.obs_usuario) {
      alert("Completa los campos obligatorios (nombre y usuario).");
      return;
    }

    try {
      const res = await fetch(`${API.actualizar}?id=${uid}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        credentials: "include",
        body: JSON.stringify(data),
      });
      const txt = await res.text(); const json = JSON.parse(txt);
      if (json.success) alert("✅ Perfil actualizado correctamente.");
      else alert("⚠️ " + (json.message || "No se pudo actualizar el perfil."));
    } catch (err) {
      console.error(err);
      alert("❌ Error de conexión con el servidor.");
    }
  });

  // ─────────────────────────────────────────────
  // SUBIR FOTO DE PERFIL
  // ─────────────────────────────────────────────
  $("upload-avatar").addEventListener("click", () => $("avatar-upload").click());
  $("avatar-upload").addEventListener("change", async (e) => {
    const uid = await ensureObserverId();
    if (!uid) { alert("No hay sesión activa."); return; }
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append("foto", file);

    try {
      const res = await fetch(`${API.foto}?id=${uid}`, {
        method: "POST",
        credentials: "include",
        body: formData,
      });
      const txt = await res.text(); const json = JSON.parse(txt);
      if (json.success) {
        $("avatar-img").src = json.url;
        alert("✅ Foto actualizada correctamente.");
      } else {
        alert("⚠️ " + (json.message || "No se pudo actualizar la foto."));
      }
    } catch (err) {
      console.error("❌ Error subiendo foto:", err);
      alert("❌ Error al subir la imagen.");
    }
  });

  // ─────────────────────────────────────────────
  // CAMBIAR CONTRASEÑA
  // ─────────────────────────────────────────────
  $("btn-cambiar-pass").addEventListener("click", async () => {
    const uid = await ensureObserverId();
    if (!uid) { alert("No hay sesión activa."); return; }

    const actual = $("pass-actual").value.trim();
    const nueva  = $("pass-nueva").value.trim();
    if (!actual || !nueva) { alert("Completa ambas contraseñas."); return; }
    if (nueva.length < 8) { alert("La nueva contraseña debe tener al menos 8 caracteres."); return; }

    try {
      const res = await fetch(API.cambiar, {
        method: "POST",
        credentials: "include",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: uid, actual, nueva })
      });
      const txt = await res.text(); const json = JSON.parse(txt);
      if (json.success) {
        $("pass-actual").value = ""; $("pass-nueva").value = "";
        alert("✅ Contraseña actualizada correctamente.");
      } else {
        alert("⚠️ " + (json.message || "No se pudo cambiar la contraseña."));
      }
    } catch (err) {
      console.error(err);
      alert("❌ Error de conexión con el servidor.");
    }
  });

  // ─────────────────────────────────────────────
  // ELIMINAR CUENTA
  // ─────────────────────────────────────────────
  $("btn-eliminar").addEventListener("click", async () => {
    const uid = await ensureObserverId();
    if (!uid) { alert("No hay sesión activa."); return; }
    if (!confirm("¿Seguro que deseas eliminar tu cuenta? Esta acción no se puede deshacer.")) return;

    try {
      const res = await fetch(API.eliminar, {
        method: "POST",
        credentials: "include",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: uid })
      });
      const txt = await res.text(); const json = JSON.parse(txt);
      if (json.success) {
        alert("🗑️ Cuenta eliminada. Cerrando sesión...");
        window.location.href = "/";
      } else {
        alert("⚠️ " + (json.message || "No se pudo eliminar la cuenta."));
      }
    } catch (err) {
      console.error(err);
      alert("❌ Error de conexión con el servidor.");
    }
  });
});
</script>

<style>
.readonly { background: #f8f9fa; color: #777; cursor: not-allowed; }
.delete-button {
  background: #d9534f; color: #fff; border: none; padding: 10px 18px;
  border-radius: 6px; font-weight: 600; cursor: pointer;
}
.delete-button:hover { background: #b52b27; }
.avatar-circle img {
  width: 120px; height: 120px; border-radius: 50%; object-fit: cover;
  border: 3px solid #dce6dc; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.form-inline { display: grid; grid-template-columns: 1fr 1fr auto; gap: .6rem; align-items: center; }
.perfil-input { width: 100%; }
</style>
