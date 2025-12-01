<?php
use yii\helpers\Html;
use app\helpers\SitioUtilidades;

// Título
$this->title = !empty($pagina->pag_titulo) ? $pagina->pag_titulo : "Detectar una Especie";

/**
 * Carga configuración compartida
 */
define('ECO_ENV_INCLUDED', true);
$envPath = Yii::getAlias('@app') . '/config/ecolens_env.php';
if (file_exists($envPath)) {
    $env = require $envPath;
} else {
    $isLocal = preg_match('/^(localhost|127\.0\.0\.1)$/i', $_SERVER['HTTP_HOST'] ?? '') === 1;
    $env = [
        'isLocal'   => $isLocal,
        'API_BASE'  => $isLocal ? 'http://localhost:8888/ecolens.site/panel-admin/web' : 'https://ecolens.site/panel-admin/web',
        'SITE_BASE' => $isLocal ? 'http://localhost:8888/ecolens.site/sitio/web'       : 'https://ecolens.site/sitio/web',
        'endpoints' => []
    ];
}

$API_BASE   = rtrim($env['API_BASE'], '/');
$SITE_BASE  = rtrim($env['SITE_BASE'], '/');
$API_PREDICT = $API_BASE . '/api/ia/predict';
$API_WHOAMI  = $env['endpoints']['whoami']    ?? ($API_BASE . '/api/observador/whoami');
$API_REG     = $env['endpoints']['registrar'] ?? ($API_BASE . '/api/deteccion/registrar');
?>

<section class="upload-section container">
  <h1>Detectar una Especie</h1>
  <p>Sube una foto clara de la fauna chilena para su identificación.</p>

  <div class="upload-form">
    <div class="upload-box" role="button" tabindex="0"
         onclick="document.getElementById('image-upload').click()"
         onkeypress="if(event.key==='Enter'||event.key===' '){document.getElementById('image-upload').click();}">
      <span class="upload-icon">📸</span>
      <p>Haz clic para subir una imagen</p>
      <p id="file-name" class="file-name"></p>
      <input type="file" id="image-upload" accept="image/jpeg,image/png,image/heic,image/heif,image/webp,image/avif,image/jxl" class="file-input" />
    </div>

    <div id="image-preview" class="image-preview">
      <h3>Vista Previa:</h3>
      <img id="preview-image" src="" alt="Vista previa de la imagen a detectar" />
    </div>

    <a href="#" id="detect-button" class="cta-button" style="display:none">Analizar Imagen</a>
    <div id="result-box"></div>
  </div>
</section>

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const API = {
    PREDICT:   <?= json_encode($API_PREDICT, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>,
    REGISTRAR: <?= json_encode($API_REG,     JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>,
    WHOAMI:    <?= json_encode($API_WHOAMI,  JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>,
    MIS_DET:   <?= json_encode($SITE_BASE . '/mis-detecciones', JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>,
  };

  const uploadInput = document.getElementById("image-upload");
  const detectBtn   = document.getElementById("detect-button");
  const preview     = document.getElementById("image-preview");
  const previewImg  = document.getElementById("preview-image");
  const resultBox   = document.getElementById("result-box");
  const fileNameEl  = document.getElementById("file-name");

  // Mostrar preview
  uploadInput.addEventListener("change", (e) => {
    const file = e.target.files && e.target.files[0];
    if (!file) {
      detectBtn.style.display = "none";
      preview.style.display = "none";
      resultBox.innerHTML = "";
      fileNameEl.textContent = "";
      return;
    }
    fileNameEl.textContent = file.name;
    detectBtn.style.display = "inline-flex";
    detectBtn.disabled = false;

    const blobUrl = URL.createObjectURL(file);
    previewImg.src = blobUrl;
    preview.style.display = "block";
    resultBox.innerHTML = "";
    previewImg.onload = () => { try { URL.revokeObjectURL(blobUrl); } catch(_){} };
  });

  // Acción principal
  detectBtn.addEventListener("click", async (e) => {
    e.preventDefault();
    const file = uploadInput.files[0];
    if (!file) { alert("Por favor selecciona una imagen primero."); return; }

    detectBtn.classList.add("is-hidden");
    document.body.classList.add("busy");
    resultBox.innerHTML = `
      <div id="loading-spinner">
        <div class="spinner"></div>
        <p>Analizando imagen, por favor espera...</p>
      </div>
    `;

    try {
      let userId = null;
      try {
        const whoRes = await fetch(API.WHOAMI, { credentials: "include" });
        const whoTxt = await whoRes.text();
        const who = JSON.parse(whoTxt);
        if (who?.authenticated && who.id) userId = who.id;
      } catch (_) {}

      // === 1) IA predict ===
      const fdModel = new FormData();
      fdModel.append("image", file);
      const predRes = await fetch(API.PREDICT, { method: "POST", body: fdModel });
      const rawText = await predRes.text();
      let pred = JSON.parse(rawText);

      if (!pred || pred.error) throw new Error(pred?.error || "Error en el servicio de IA");

      // Datos base del router
      const taxonName    = pred.taxon_predicted || "Desconocido";
      const taxonConfRaw = Number(pred.taxon_confidence || 0);
      const msRouterRaw  = pred.inference_router_ms ?? null;
      const msExpertRaw  = pred.inference_expert_ms ?? null;

      // Datos de experto: crudo + meta
      const speciesPredicted    = pred.species_predicted || null;   // solo si pasa umbral
      const speciesTop1         = pred.species_top1 || null;        // siempre que haya experto
      const speciesTop1ConfRaw  = pred.species_top1_confidence != null
                                  ? Number(pred.species_top1_confidence)
                                  : null;
      const expertMinConf       = pred.expert_min_conf ?? null;
      const meta                = pred._meta || {};
      const esConcluyente       = typeof meta.es_concluyente !== "undefined"
                                  ? !!meta.es_concluyente
                                  : (speciesPredicted !== null);

      // Nombre que mostraremos en la UI: siempre que exista top1, lo usamos
      const speciesDisplayName  = speciesTop1 || speciesPredicted || null;

      // Confianza que mostraremos: top1 si viene, sino species_confidence
      const speciesConfRaw = speciesDisplayName
        ? (speciesTop1ConfRaw != null
            ? speciesTop1ConfRaw
            : Number(pred.species_confidence || 0))
        : 0;

      // IDs y metadatos adicionales (opcionales según backend)
      const modelRouterId = pred.model_id         ?? "";
      const modelExpertId = pred.model_expert_id  ?? "";
      const taxonId       = pred.taxon_id         ?? "";
      const speciesId     = pred.species_id       ?? "";

      // === 2) Geolocalización ===
      let lat = "", lon = "", locationName = "Ubicación no disponible";
      if (navigator.geolocation) {
        await new Promise((resolve) => {
          navigator.geolocation.getCurrentPosition(
            async (pos) => {
              lat = +pos.coords.latitude.toFixed(6);
              lon = +pos.coords.longitude.toFixed(6);
              try {
                const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`);
                const geo = await res.json();
                locationName = geo.display_name || "Ubicación detectada";
              } catch {}
              resolve();
            },
            () => resolve(),
            { timeout: 4000 }
          );
        });
      }

      // === 3) Mostrar resultado ===
      const taxonConfPct   = (taxonConfRaw * 100).toFixed(1);
      const speciesConfPct = speciesDisplayName ? (speciesConfRaw * 100).toFixed(1) : null;
      const msRouterUI     = msRouterRaw != null ? Math.round(msRouterRaw) : null;
      const msExpertUI     = msExpertRaw != null ? Math.round(msExpertRaw) : null;
      const expertMinPct   = expertMinConf != null ? (expertMinConf * 100).toFixed(1) : null;

      resultBox.innerHTML = `
        <div class="result-card">
          <h3>🔍 Resultado del Análisis</h3>

          <p><strong>Taxón:</strong> ${taxonName} (${taxonConfPct}%)</p>
          <div class="confidence-bar"><div class="confidence-fill" style="width:${taxonConfPct}%"></div></div>

          ${
            speciesDisplayName
            ? `
              <p>
                <strong>Especie ${esConcluyente ? "" : "probable"}:</strong>
                ${speciesDisplayName} (${speciesConfPct}%)
              </p>
              <div class="confidence-bar">
                <div class="confidence-fill" style="width:${speciesConfPct}%"></div>
              </div>
              ${
                !esConcluyente
                  ? `<p style="font-size:0.85rem;color:#b45309;margin-top:.35rem;">
                       ⚠ Resultado de baja confianza. Interprétalo como sugerencia, no como identificación confirmada.
                     </p>`
                  : ""
              }
              ${
                expertMinPct
                  ? `<p style="font-size:0.8rem;color:#6b7280;margin-top:.15rem;">
                       Umbral del modelo experto: ${expertMinPct} %
                     </p>`
                  : ""
              }
            `
            : ""
          }

          <p style="color:#6b7280;font-size:0.9rem;margin-top:.35rem">
            ⏱ Router: ${msRouterUI ?? "–"} ms
            ${msExpertUI !== null ? `&nbsp;|&nbsp; ⏱ Experto: ${msExpertUI} ms` : ""}
          </p>

          <hr style="margin:1rem 0;opacity:0.3;">

          <p><strong>Ubicación:</strong><br>${locationName}</p>
          <p style="font-size:0.9rem;color:#555;">(${lat || "?"}, ${lon || "?"})</p>
          <div id="map-container" style="height:250px;margin-top:1rem;border-radius:10px;overflow:hidden;"></div>
        </div>`;

      if (lat && lon) {
        const map = L.map("map-container").setView([lat, lon], 13);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
          attribution: "&copy; OpenStreetMap contributors"
        }).addTo(map);
        L.marker([lat, lon]).addTo(map);
      }

      // === 4) Registrar detección (negocio): seguimos usando species_predicted, no top1 ===
      const ua = navigator.userAgent;
      const deviceType = /Mobi|Android/i.test(ua) ? "mobile" : "desktop";
      const os = navigator.platform.includes("Win") ? "Windows"
               : navigator.platform.includes("Mac") ? "macOS"
               : navigator.platform.includes("Linux") ? "Linux" : "Otro";
      const browser = ua.includes("Firefox") ? "Firefox"
                   : ua.includes("Chrome")  ? "Chrome"
                   : ua.includes("Safari")  ? "Safari"
                   : ua.includes("Edge")    ? "Edge" : "Otro";

      const fd = new FormData();
      fd.append("det_latitud", lat);
      fd.append("det_longitud", lon);
      fd.append("det_ubicacion_textual", locationName);
      fd.append("det_obs_id", userId ?? "");
      fd.append("det_fuente", "web");
      fd.append("det_tax_id", taxonId);
      fd.append("det_esp_id", speciesId);
      fd.append("taxon_predicted", taxonName || "");
      fd.append("species_predicted", speciesPredicted || "");
      fd.append("det_modelo_router_id", modelRouterId);
      fd.append("det_modelo_experto_id", modelExpertId);
      fd.append("router_model", pred.router_model || pred.model_name || "efficientnet_b5");
      fd.append("expert_model", pred.expert_model || "desconocido");
      fd.append("det_confianza_router", taxonConfRaw);
      fd.append("det_confianza_experto", speciesConfRaw);
      fd.append("det_tiempo_router_ms", msRouterRaw != null ? Math.round(msRouterRaw) : "");
      fd.append("det_tiempo_experto_ms", msExpertRaw != null ? Math.round(msExpertRaw) : "");
      fd.append("det_dispositivo_tipo", deviceType);
      fd.append("det_sistema_operativo", os);
      fd.append("det_navegador", browser);
      fd.append("imagen", file);

      const apiRes  = await fetch(API.REGISTRAR, { method: "POST", body: fd, credentials: "include" });
      const apiText = await apiRes.text();
      let apiData = {};
      try { apiData = JSON.parse(apiText); } catch { throw new Error("JSON inválido del backend"); }

      if (!apiData.success) {
        resultBox.innerHTML += `<div class="result-error">🚫 Error guardando la detección:<br>${apiData.message || "Error desconocido."}</div>`;
      } else {
        resultBox.innerHTML += `<p style="margin-top:1rem;color:green;font-weight:600;">✅ Detección registrada con ID #${apiData.id}</p>`;
      }

      // === 5) Redirigir / sugerir ficha de la especie basada en speciesDisplayName (no en BD) ===
      if (speciesDisplayName) {
        try {
          const slugUrl = API.PREDICT.replace('/api/ia/predict','/api/contenido/slug-especie')
                           + '?nombre=' + encodeURIComponent(speciesDisplayName);

          const slugRes = await fetch(slugUrl, {
            headers: { 'X-Api-Key': 'ABCabc123' }
          });
          const slugJson = await slugRes.json();

          if (slugJson?.success && slugJson.slug) {
            const fichaBase = API.MIS_DET.replace('/mis-detecciones','/taxonomias/');
            const fichaUrl  = `${fichaBase}${slugJson.taxSlug}/${slugJson.slug}`;
            resultBox.innerHTML += `
              <p style="margin-top:1rem;">
                <a href="${fichaUrl}" class="mini-link" style="color:#173b35;font-weight:600;text-decoration:none;">
                  🔗 Ver ficha de ${speciesDisplayName}
                </a>
              </p>`;
          } else {
            resultBox.innerHTML += `<p style="margin-top:1rem;color:#555;">ℹ️ No se encontró la ficha de esta especie.</p>`;
          }
        } catch (err) {
          console.warn("Error obteniendo slug:", err);
          resultBox.innerHTML += `<p style="margin-top:1rem;color:#555;">⚠️ No se pudo cargar la ficha de la especie.</p>`;
        }
      }

    } catch (err) {
      console.error("❌ Error general:", err);
      resultBox.innerHTML = `<div class="result-error">🚫 Error:<br>${err.message}</div>`;
    } finally {
      document.body.classList.remove("busy");
      detectBtn.classList.remove("is-hidden");
    }
  });
});
</script>

<style>
.image-preview { display:none; margin-top:1rem; margin-bottom:1rem; text-align:center; }
.image-preview img { max-width:100%; height:auto; max-height:520px; object-fit:contain; border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,.12); }
#result-box { margin-top:1.5rem; text-align:center; }
.result-card { display:inline-block; background:#fff; border:1px solid #ddd; border-radius:12px; padding:1.5rem 2rem; box-shadow:0 4px 12px rgba(0,0,0,0.1); text-align:center; font-family:"Nunito Sans",sans-serif; animation:fadeIn 0.4s ease; max-width:520px; }
.result-card h3 { color:#1f2937; margin-bottom:0.8rem; }
.confidence-bar { width:100%; background:#eee; height:12px; border-radius:6px; overflow:hidden; margin:0.5rem 0 0.8rem; }
.confidence-fill { height:100%; background:linear-gradient(90deg,#3b82f6,#22c55e); transition:width 0.6s ease; }
.result-error { color:#b91c1c; background:#fee2e2; border:1px solid #fca5a5; padding:1rem; border-radius:8px; }
.cta-button[disabled]{ pointer-events:none; opacity:.5; }

.upload-box{ border:2px dashed #d1d5db; border-radius:12px; padding:1.5rem; text-align:center; cursor:pointer; transition:background .2s ease,border-color .2s ease; }
.upload-box:hover{ background:#f9fafb; border-color:#9ca3af; }
.upload-icon{ font-size:2rem; display:block; margin-bottom:.25rem; }
.file-input{ display:none; }
.mini-link{ font-size:.95rem; padding:.5rem .85rem; }

/* Ocultar botón y bloquear interacción */
.is-hidden { display:none !important; visibility:hidden !important; pointer-events:none !important; }
body.busy { cursor:wait !important; }
body.busy * { pointer-events:none !important; user-select:none !important; }

/* Spinner */
#loading-spinner { display:flex; flex-direction:column; align-items:center; justify-content:center; margin-top:2rem; color:#374151; font-family:"Nunito Sans",sans-serif; text-align:center; }
.spinner { border:4px solid #e5e7eb; border-top:4px solid #45AD82; border-radius:50%; width:48px; height:48px; animation:spin 1s linear infinite; margin-bottom:0.8rem; }
@keyframes spin { 0%{transform:rotate(0deg);}100%{transform:rotate(360deg);} }
</style>