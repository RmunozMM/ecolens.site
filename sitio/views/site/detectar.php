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

$API_BASE    = rtrim($env['API_BASE'], '/');
$SITE_BASE   = rtrim($env['SITE_BASE'], '/');
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
      <h3>Vista Previa</h3>
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

      // CTA post detección (en vez de like/dislike)
      const renderPostDetectionCTA = (fichaUrl, speciesName, deteccionId) => {
        const safeName = speciesName || "la especie detectada";

        resultBox.innerHTML += `
          <div class="feedback-wrapper">
            <p class="feedback-title">
              Tu detección se guardó en tu historial de observaciones.
            </p>
            <p class="feedback-subtitle">
              Puedes revisar con más calma el resultado para <strong>${safeName}</strong> en tu sección "Mis detecciones".
            </p>
            <div class="feedback-actions">
              <a href="${API.MIS_DET}" class="feedback-btn feedback-link">
                Ver mis detecciones
              </a>
            </div>
          </div>
        `;
      };

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
          <h3>🔍 Resultado del análisis</h3>

          <p class="result-taxon">
            <span class="result-label">Taxón:</span>
            <span class="result-value">${taxonName}</span>
            <span class="result-confidence">(${taxonConfPct}%)</span>
          </p>
          <div class="confidence-bar">
            <div class="confidence-fill" style="width:${taxonConfPct}%"></div>
          </div>

          ${
            speciesDisplayName
            ? `
              <p class="result-species">
                <span class="result-label">Especie ${esConcluyente ? "" : "probable"}:</span>
                <span class="result-value">${speciesDisplayName}</span>
                <span class="result-confidence">(${speciesConfPct}%)
                </span>
              </p>
              <div class="confidence-bar">
                <div class="confidence-fill" style="width:${speciesConfPct}%"></div>
              </div>
              ${
                !esConcluyente
                  ? `<p class="low-confidence-hint">
                       ⚠ Resultado de baja confianza. Interprétalo como sugerencia, no como identificación confirmada.
                     </p>`
                  : ""
              }
              ${
                expertMinPct
                  ? `<p class="expert-threshold">
                       Umbral del modelo experto: ${expertMinPct} %
                     </p>`
                  : ""
              }
            `
            : ""
          }

          <p class="inference-times">
            ⏱ Router: ${msRouterUI ?? "–"} ms
            ${msExpertUI !== null ? `&nbsp;|&nbsp; ⏱ Experto: ${msExpertUI} ms` : ""}
          </p>

          <hr class="result-divider">

          <p class="location-title"><strong>Ubicación:</strong></p>
          <p class="location-text">${locationName}</p>
          <p class="location-coords">(${lat || "?"}, ${lon || "?"})</p>
          <div id="map-container"></div>
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
        resultBox.innerHTML += `<p class="result-success">✅ Detección registrada con ID #${apiData.id}</p>`;
      }

      // === 5) Ficha especie + CTA a Mis detecciones ===
      let fichaUrl = null;

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
            fichaUrl  = `${fichaBase}${slugJson.taxSlug}/${slugJson.slug}`;
            resultBox.innerHTML += `
              <p class="species-link-wrapper">
                <a href="${fichaUrl}" class="mini-link">
                  🔗 Ver ficha de ${speciesDisplayName}
                </a>
              </p>`;
          } else {
            resultBox.innerHTML += `<p class="species-link-fallback">ℹ️ No se encontró la ficha de esta especie.</p>`;
          }
        } catch (err) {
          console.warn("Error obteniendo slug:", err);
          resultBox.innerHTML += `<p class="species-link-fallback">⚠️ No se pudo cargar la ficha de la especie.</p>`;
        }
      }

      // CTA final a Mis detecciones (sin pedir juicio explícito)
      renderPostDetectionCTA(fichaUrl, speciesDisplayName, apiData.id ?? null);

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
/* Layout general de la sección */
.upload-section {
  padding: 2.5rem 1.25rem 3rem;
  text-align: center;
}

.upload-section h1 {
  font-family: "Lora", "Georgia", serif;
  font-size: clamp(1.8rem, 2.3vw, 2.3rem);
  margin-bottom: 0.6rem;
  color: #173b35;
}

.upload-section > p {
  margin: 0 auto 1.5rem;
  max-width: 640px;
  font-size: 0.98rem;
  color: #4b5563;
}

.upload-form {
  max-width: 640px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

/* Caja de subida */
.upload-box {
  border: 2px dashed #cbd5f5;
  border-radius: 16px;
  padding: 1.75rem 1.5rem;
  text-align: center;
  cursor: pointer;
  background: radial-gradient(circle at top left, rgba(23, 59, 53, 0.06), transparent),
              rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(8px);
  transition:
    background 0.2s ease,
    border-color 0.2s ease,
    box-shadow 0.2s ease,
    transform 0.15s ease;
  box-shadow: 0 14px 40px rgba(15, 23, 42, 0.07);
}

.upload-box:hover,
.upload-box:focus-within {
  border-color: #45ad82;
  background: radial-gradient(circle at top left, rgba(69, 173, 130, 0.18), transparent),
              rgba(255, 255, 255, 0.95);
  box-shadow: 0 18px 55px rgba(15, 23, 42, 0.16);
  transform: translateY(-2px);
}

.upload-icon {
  font-size: 2.4rem;
  display: block;
  margin-bottom: 0.35rem;
}

.upload-box p {
  margin: 0;
  font-size: 0.95rem;
  color: #374151;
}

.file-name {
  margin-top: 0.6rem;
  font-size: 0.9rem;
  color: #6b7280;
  font-style: italic;
  word-break: break-all;
}

.file-input {
  display: none;
}

/* Vista previa */
.image-preview {
  display: none;
  margin-top: 0.25rem;
  margin-bottom: 0.75rem;
  text-align: center;
}

.image-preview h3 {
  margin-bottom: 0.5rem;
  font-size: 1rem;
  color: #111827;
}

.image-preview img {
  max-width: 100%;
  height: auto;
  max-height: 520px;
  object-fit: contain;
  border-radius: 16px;
  box-shadow: 0 18px 45px rgba(15, 23, 42, 0.25);
}

/* Botón principal */
.cta-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.45rem;
  padding: 0.7rem 1.6rem;
  border-radius: 999px;
  border: none;
  text-decoration: none;
  font-size: 0.98rem;
  font-weight: 600;
  background: linear-gradient(135deg, #173b35, #45ad82);
  color: #f9fafb;
  box-shadow: 0 12px 30px rgba(23, 59, 53, 0.4);
  cursor: pointer;
  transition:
    transform 0.15s ease,
    box-shadow 0.15s ease,
    background 0.2s ease,
    opacity 0.2s ease;
}

.cta-button:hover {
  transform: translateY(-1px);
  box-shadow: 0 18px 40px rgba(23, 59, 53, 0.55);
  background: linear-gradient(135deg, #145046, #45ad82);
}

.cta-button:active {
  transform: translateY(0);
  box-shadow: 0 8px 20px rgba(23, 59, 53, 0.35);
}

.cta-button[disabled] {
  pointer-events: none;
  opacity: 0.55;
}

/* Resultado */
#result-box {
  margin-top: 1.5rem;
  text-align: center;
}

.result-card {
  display: inline-block;
  background: rgba(255, 255, 255, 0.98);
  border: 1px solid rgba(209, 213, 219, 0.8);
  border-radius: 16px;
  padding: 1.5rem 2rem;
  box-shadow: 0 20px 55px rgba(15, 23, 42, 0.25);
  text-align: left;
  font-family: "Nunito Sans", system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
  animation: fadeIn 0.35s ease-out;
  max-width: 560px;
  width: 100%;
}

.result-card h3 {
  color: #111827;
  margin-bottom: 0.8rem;
  font-size: 1.15rem;
}

/* Filas de resultado */
.result-taxon,
.result-species {
  margin: 0.4rem 0 0.1rem;
  font-size: 0.96rem;
  color: #111827;
}

.result-label {
  font-weight: 600;
  color: #111827;
}

.result-value {
  margin-left: 0.25rem;
  font-weight: 500;
}

.result-confidence {
  margin-left: 0.25rem;
  font-size: 0.9rem;
  color: #6b7280;
}

/* Barras de confianza */
.confidence-bar {
  width: 100%;
  background: #e5e7eb;
  height: 10px;
  border-radius: 999px;
  overflow: hidden;
  margin: 0.35rem 0 0.7rem;
}

.confidence-fill {
  height: 100%;
  background: linear-gradient(90deg, #45ad82, #8bd6b0);
  transition: width 0.6s ease;
}

/* Mensajes extra */
.low-confidence-hint {
  font-size: 0.83rem;
  color: #b45309;
  margin-top: 0.2rem;
}

.expert-threshold {
  font-size: 0.8rem;
  color: #6b7280;
  margin-top: 0.1rem;
}

.inference-times {
  color: #6b7280;
  font-size: 0.9rem;
  margin-top: 0.4rem;
}

/* Separador */
.result-divider {
  margin: 1rem 0;
  opacity: 0.25;
  border: none;
  border-top: 1px solid #e5e7eb;
}

/* Ubicación y mapa */
.location-title {
  margin-bottom: 0.15rem;
}

.location-text {
  margin: 0;
  font-size: 0.92rem;
  color: #374151;
}

.location-coords {
  margin: 0.1rem 0 0.6rem;
  font-size: 0.8rem;
  color: #9ca3af;
}

#map-container {
  height: 250px;
  margin-top: 0.25rem;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid rgba(209, 213, 219, 0.9);
}

/* Estados de API */
.result-error {
  color: #991b1b;
  background: #fee2e2;
  border: 1px solid #fecaca;
  padding: 0.9rem 1rem;
  border-radius: 10px;
  font-size: 0.9rem;
  margin-top: 0.9rem;
  text-align: left;
}

.result-success {
  margin-top: 1rem;
  color: #166534;
  font-weight: 600;
  font-size: 0.9rem;
}

/* Enlace a ficha */
.mini-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
  padding: 0.4rem 0.85rem;
  border-radius: 999px;
  text-decoration: none;
  font-weight: 600;
  color: #173b35;
  background: rgba(23, 59, 53, 0.05);
  border: 1px solid rgba(23, 59, 53, 0.15);
  transition:
    background 0.2s ease,
    border-color 0.2s ease,
    transform 0.15s ease,
    box-shadow 0.15s ease;
}

.mini-link:hover {
  background: rgba(23, 59, 53, 0.1);
  border-color: rgba(23, 59, 53, 0.35);
  box-shadow: 0 8px 20px rgba(15, 23, 42, 0.25);
  transform: translateY(-1px);
}

.species-link-wrapper {
  margin-top: 1rem;
}

.species-link-fallback {
  margin-top: 1rem;
  font-size: 0.86rem;
  color: #4b5563;
}

/* Bloque “post detección” */
.feedback-wrapper {
  margin-top: 1.1rem;
  text-align: center;
}

.feedback-title {
  margin: 0 0 0.2rem;
  font-size: 0.9rem;
  color: #374151;
}

.feedback-subtitle {
  margin: 0 0 0.6rem;
  font-size: 0.86rem;
  color: #4b5563;
}

.feedback-actions {
  display: flex;
  justify-content: center;
  gap: 0.6rem;
  flex-wrap: wrap;
}

.feedback-btn {
  border-radius: 999px;
  padding: 0.35rem 0.9rem;
  font-size: 0.88rem;
  border: 1px solid transparent;
  cursor: pointer;
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition:
    background 0.15s ease,
    border-color 0.15s ease,
    box-shadow 0.15s ease,
    transform 0.12s ease;
}

.feedback-link {
  background: #eef2ff;
  border-color: #c7d2fe;
  color: #312e81;
}

.feedback-link:hover {
  background: #e0e7ff;
  box-shadow: 0 6px 14px rgba(37, 99, 235, 0.25);
  transform: translateY(-1px);
}

/* Ocultar botón y bloquear interacción durante procesamiento */
.is-hidden {
  display: none !important;
  visibility: hidden !important;
  pointer-events: none !important;
}

body.busy {
  cursor: wait !important;
}

body.busy * {
  pointer-events: none !important;
  user-select: none !important;
}

/* Spinner */
#loading-spinner {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  margin-top: 2rem;
  color: #374151;
  font-family: "Nunito Sans", system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
  text-align: center;
}

.spinner {
  border: 4px solid #e5e7eb;
  border-top: 4px solid #45ad82;
  border-radius: 50%;
  width: 46px;
  height: 46px;
  animation: spin 1s linear infinite;
  margin-bottom: 0.8rem;
}

/* Animaciones */
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(6px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsivo */
@media (max-width: 640px) {
  .upload-section {
    padding-top: 2rem;
  }

  .result-card {
    padding: 1.25rem 1.1rem 1.4rem;
    border-radius: 14px;
  }

  #map-container {
    height: 200px;
  }
}
</style>
