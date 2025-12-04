<?php
/** @var int $id */

use yii\helpers\Html;

/**
 * Carga entorno compartido (igual que en Mis detecciones)
 */
function ecolens_env_load_detalle(): array {
    $candidatos = [
        Yii::getAlias('@app') . '/config/ecolens_env.php',
        dirname(Yii::getAlias('@app')) . '/panel-admin/config/ecolens_env.php',
    ];
    foreach ($candidatos as $p) {
        if (is_file($p)) {
            $env = require $p;
            if (is_array($env)) return $env;
        }
    }
    $host    = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $isLocal = (bool)preg_match('/^(localhost|127\.0\.0\.1)(:\d+)?$/i', $host);
    return [
        'isLocal'   => $isLocal,
        'API_BASE'  => ($isLocal ? 'http://localhost:8888' : 'https://ecolens.site') . '/panel-admin/web',
        'SITE_BASE' => ($isLocal ? 'http://localhost:8888' : 'https://ecolens.site') . '/sitio/web',
        'endpoints' => [],
    ];
}

$env       = ecolens_env_load_detalle();
$API_BASE  = rtrim($env['API_BASE'], '/');
$SITE_BASE = rtrim($env['SITE_BASE'], '/');

// Endpoints
$API_DETALLE  = $env['endpoints']['detalle']  ?? ($API_BASE . '/api/deteccion/detalle');
$API_FEEDBACK = $env['endpoints']['feedback'] ?? ($API_BASE . '/api/deteccion/feedback');

$this->title = "Detalle de Detección #{$id}";
?>

<section class="detalle-deteccion container">

  <nav class="breadcrumb">
    <a href="<?= Html::encode($SITE_BASE) ?>">Inicio</a>
    <span class="sep">/</span>
    <a href="<?= Html::encode($SITE_BASE) ?>/mis-detecciones">Mis detecciones</a>
    <span class="sep">/</span>
    <span class="current">Detección #<?= Html::encode($id) ?></span>
  </nav>
  <h1>Detalle de detección</h1>
  <div id="det-meta" class="det-meta"></div>
  <div id="det-content">Cargando...</div>
  <div id="det-error" style="display:none;color:#b91c1c;margin-top:1rem;"></div>
</section>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", async () => {
  const DET_ID      = <?= json_encode($id) ?>;
  const API_DETALLE = <?= json_encode($API_DETALLE,  JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;
  const API_FEEDBACK= <?= json_encode($API_FEEDBACK, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;
  const SITE_BASE   = <?= json_encode($SITE_BASE,   JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;

  const meta = document.getElementById("det-meta");
  const cont = document.getElementById("det-content");
  const err  = document.getElementById("det-error");

  const txt = v => (v && String(v).trim().length) ? String(v) : "No registrado";
  const txtUbic = v => (v && String(v).trim().length) ? String(v) : "No especificada";

  const dev = (disp, so) => {
    const d = txt(disp);
    const s = txt(so);
    if (d === "No registrado" && s === "No registrado") return "No identificado";
    if (d !== "No registrado" && s !== "No registrado") return `${d} (${s})`;
    return d !== "No registrado" ? d : s;
  };

  const safeDate = v => {
    try {
      return v ? new Date(v).toLocaleString("es-CL") : "No registrada";
    } catch {
      return "No registrada";
    }
  };

  const hasCoords = (lat, lng) => Number.isFinite(+lat) && Number.isFinite(+lng);

  const pickField = (obj, ...names) => {
    for (const n of names) {
      if (obj && obj[n] !== undefined && obj[n] !== null) {
        return obj[n];
      }
    }
    return null;
  };

  const toPercent = (val) => {
    const num = Number(val);
    if (!Number.isFinite(num)) return "No disponible";
    return `${(num * 100).toFixed(1)}%`;
  };

  // Bloque feedback: UI
  const applyFeedbackUI = (value) => {
    const yesBtn = document.getElementById("fb-yes");
    const noBtn  = document.getElementById("fb-no");
    const status = document.getElementById("fb-detail-status");
    if (!yesBtn || !noBtn || !status) return;

    yesBtn.classList.remove("active");
    noBtn.classList.remove("active");

    if (value === "like") {
      yesBtn.classList.add("active");
      status.textContent = "Marcaste esta identificación como correcta.";
      status.className = "fb-detail-status answered";
    } else if (value === "dislike") {
      noBtn.classList.add("active");
      status.textContent = "Marcaste esta identificación como incorrecta.";
      status.className = "fb-detail-status answered";
    } else {
      status.textContent = "Aún no has respondido. Esta respuesta es opcional y nos ayuda a mejorar el sistema.";
      status.className = "fb-detail-status pending";
    }
  };

  const sendFeedback = async (nextValue) => {
    const status = document.getElementById("fb-detail-status");
    const block  = document.getElementById("fb-block");
    if (!block) return;

    const current = block.dataset.feedback || "";
    const value   = (nextValue === current) ? "" : nextValue; // toggle

    if (status) {
      status.textContent = "Guardando respuesta...";
      status.className   = "fb-detail-status pending";
    }

    try {
      const resp = await fetch(API_FEEDBACK, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        credentials: "include",
        body: JSON.stringify({ id: DET_ID, feedback: value }),
      });
      const raw  = await resp.text();
      let data   = {};
      try { data = JSON.parse(raw); } catch (_) {}

      if (!resp.ok || data.success === false) {
        throw new Error(data.message || "Error al guardar el feedback.");
      }

      block.dataset.feedback = value || "";
      applyFeedbackUI(value || null);
    } catch (e) {
      if (status) {
        status.textContent = "No se pudo guardar tu respuesta. Intenta nuevamente.";
        status.className   = "fb-detail-status pending";
      }
    }
  };

  try {
    const url = `${API_DETALLE}?id=${encodeURIComponent(DET_ID)}`;
    const r   = await fetch(url, { credentials: "include" });
    const raw = await r.text();
    let data;
    try { data = JSON.parse(raw); }
    catch {
      throw new Error("Respuesta no válida del servidor");
    }

    const success = (data && typeof data === "object" && "success" in data)
      ? !!data.success
      : true;

    if (!success) {
      err.textContent = data.message || "No se pudo obtener la detección.";
      err.style.display = "block";
      cont.style.display = "none";
      return;
    }

    const d = data;

    const e = d.especie    || {};
    const t = d.taxonomia  || {};
    const o = d.observador || {};
    const imgDeteccion = d.imagen_deteccion || "";
    const imgEspecie   = d.imagen_especie || (e.imagen || "");

    const especieDisplayName =
      e.nombre_comun || e.nombre_cientifico || "Especie desconocida";

    const especieUrl =
      d.url_especie
      || ((t && t.slug && e && e.slug)
            ? `${SITE_BASE}/taxonomias/${t.slug}/${e.slug}`
            : null);

    const especieLinkHtml =
      especieUrl && especieDisplayName
        ? `<p class="img-label-sec">
             <a href="${especieUrl}" rel="noopener" class="especie-link">
               Ver ficha de ${especieDisplayName}
             </a>
           </p>`
        : "";

    meta.innerHTML = `
      <div class="meta-row">
        <div class="meta-left">
          <span class="badge-id">Detección #${DET_ID}</span>
        </div>
      </div>
    `;

    const grupoLabel = t?.nombre ? t.nombre : "No registrado";
    const grupoHtml = (t?.slug && t?.nombre)
      ? `<a href="${SITE_BASE}/taxonomias/${t.slug}" rel="noopener">${t.nombre}</a>`
      : grupoLabel;

    const confRouter  = toPercent(pickField(d, "conf_router", "det_confianza_router"));
    const confExperto = toPercent(pickField(d, "conf_experto", "det_confianza_experto"));

    // feedback actual desde el API (nuevo esquema: feedback.usuario)
    const fbObj         = d.feedback || {};
    const feedbackValue = fbObj.usuario || null;

    cont.innerHTML = `
      <div class="detalle-wrapper">
        <div class="detalle-imagenes">
          ${
            imgDeteccion
              ? `
                <div class="img-box">
                  <p class="img-label">📸 Tu detección</p>
                  <img src="${imgDeteccion}" alt="Foto de detección">
                </div>`
              : `
                <div class="img-box placeholder">
                  <p class="img-label">📸 Tu detección</p>
                  <div class="img-ph">Sin imagen</div>
                </div>
              `
          }
          ${
            imgEspecie
              ? `
                <div class="img-box">
                  <p class="img-label">🧬 Referencia de especie</p>
                  ${especieLinkHtml}
                  <img src="${imgEspecie}" alt="Referencia de especie">
                </div>`
              : ""
          }

          <!-- Bloque feedback detalle BAJO LAS IMÁGENES -->
          <div id="fb-block" class="fb-detail-block" data-feedback="">
            <h3>¿Coincide la especie sugerida con lo que observaste?</h3>
            <div class="fb-detail-buttons">
              <button id="fb-yes" type="button" class="fb-detail-btn fb-yes">
                👍 <span>Sí, coincide</span>
              </button>
              <button id="fb-no"  type="button" class="fb-detail-btn fb-no">
                👎 <span>No coincide</span>
              </button>
            </div>
            <p id="fb-detail-status" class="fb-detail-status pending"></p>
          </div>
        </div>

        <div class="detalle-info">
          <h2>${especieDisplayName}</h2>

          <p class="taxon"><strong>Grupo taxonómico:</strong> ${grupoHtml}</p>
          <p><strong>Fecha:</strong> ${safeDate(d.fecha)}</p>
          <p><strong>Ubicación:</strong> ${txtUbic(d.ubicacion)}</p>
          <p><strong>Confianza Router:</strong> ${confRouter}</p>
          <p><strong>Confianza Experto:</strong> ${confExperto}</p>
          <p><strong>Fuente:</strong> ${txt(d.fuente)}</p>
          <p><strong>Dispositivo:</strong> ${dev(d.dispositivo, d.sistema)}</p>
          <p><strong>Navegador:</strong> ${
            txt(d.navegador) !== "No registrado" ? d.navegador : "No identificado"
          }</p>
          <p><strong>Observador:</strong> ${
            (o && (o.nombre || o.usuario)) ? (o.nombre || o.usuario) : "Anónimo"
          }</p>

          ${e.descripcion ? `<div class="descripcion">${e.descripcion}</div>` : ""}
        </div>
      </div>

      ${
        hasCoords(d.latitud, d.longitud)
          ? `<div id="mapa" class="mapa"></div>`
          : `<p class="hint-muted">Sin coordenadas disponibles para mostrar mapa.</p>`
      }

      <a href="<?php echo Html::encode($SITE_BASE); ?>/mis-detecciones" class="btn-volver">← Volver</a>
    `;

    // feedback inicial
    const fbBlock = document.getElementById("fb-block");
    if (fbBlock) {
      fbBlock.dataset.feedback = feedbackValue || "";
      applyFeedbackUI(feedbackValue || null);
    }

    const yesBtn = document.getElementById("fb-yes");
    const noBtn  = document.getElementById("fb-no");
    if (yesBtn) yesBtn.addEventListener("click", () => sendFeedback("like"));
    if (noBtn)  noBtn.addEventListener("click", () => sendFeedback("dislike"));

    // Mapa
    if (hasCoords(d.latitud, d.longitud)) {
      const map = L.map('mapa').setView([+d.latitud, +d.longitud], 14);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(map);

      L.marker([+d.latitud, +d.longitud]).addTo(map)
        .bindPopup(
          `<strong>${especieDisplayName}</strong><br>${txtUbic(d.ubicacion)}`
        );
    }

  } catch (e) {
    console.error(e);
    err.textContent = "Error de conexión al cargar la detección.";
    err.style.display = "block";
    cont.style.display = "none";
  }
});
</script>

<style>
.detalle-deteccion {
  padding: 2rem 1rem;
  font-family: "Nunito Sans", sans-serif;
}

.detalle-deteccion h1 {
  font-family: "Lora", "Georgia", serif;
  font-size: clamp(1.6rem, 2vw, 2rem);
  color: #173b35;
  margin-bottom: 0.75rem;
}

.detalle-wrapper {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.detalle-imagenes {
  display: flex;
  flex-direction: column;
  flex-wrap: wrap;
  gap: 1.2rem;
  justify-content: flex-start;
}

.img-box {
  flex: 1 1 300px;
  text-align: center;
}

.img-box img {
  width: 100%;
  max-width: 400px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,.15);
  object-fit: cover;
}

.img-label {
  margin-bottom: .25rem;
  font-weight: 600;
  color: #374151;
}

.img-label-sec {
  margin: 0 0 .5rem;
}

.especie-link {
  font-size: 0.9rem;
  font-weight: 600;
  text-decoration: none;
  color: #173b35;
  background: rgba(23, 59, 53, 0.05);
  padding: 0.25rem 0.7rem;
  border-radius: 999px;
  border: 1px solid rgba(23, 59, 53, 0.18);
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  transition:
    background 0.2s ease,
    border-color 0.2s ease,
    transform 0.15s ease,
    box-shadow 0.15s ease;
}

.especie-link:hover {
  background: rgba(23, 59, 53, 0.1);
  border-color: rgba(23, 59, 53, 0.4);
  box-shadow: 0 8px 20px rgba(15, 23, 42, 0.25);
  transform: translateY(-1px);
}

.detalle-info {
  flex: 1;
  min-width: 280px;
}

.detalle-info h2 {
  margin-top: 0;
  color: #1f2937;
  font-size: 1.3rem;
}

.detalle-info .taxon {
  color: #64748b;
  font-size: 0.95rem;
}

.detalle-info .taxon a {
  color: #2563eb;
  text-decoration: none;
  font-weight: 600;
}

.detalle-info .taxon a:hover {
  text-decoration: underline;
}

.descripcion {
  margin-top: 1rem;
  line-height: 1.5;
  color: #374151;
}

/* Bloque feedback detalle BAJO LAS IMÁGENES */
.fb-detail-block {
  margin-top: 0.5rem;
  padding: 1rem 1.1rem;
  border-radius: 14px;
  background: #f9fafb;
  border: 1px solid #e5e7eb;
}

.fb-detail-block h3 {
  margin: 0 0 0.3rem;
  font-size: 0.95rem;
  color: #111827;
}

.fb-detail-hint {
  margin: 0 0 0.7rem;
  font-size: 0.8rem;
  color: #6b7280;
}

.fb-detail-buttons {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 0.35rem;
}

.fb-detail-btn {
  border-radius: 999px;
  border: 1px solid #d1d5db;
  padding: 0.35rem 0.8rem;
  font-size: 0.85rem;
  font-weight: 600;
  cursor: pointer;
  background: #ffffff;
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  transition:
    background 0.15s ease,
    border-color 0.15s ease,
    box-shadow 0.15s ease,
    transform 0.1s ease;
}

.fb-detail-btn span {
  white-space: nowrap;
}

.fb-detail-btn.fb-yes.active {
  background: #dcfce7;
  border-color: #22c55e;
  box-shadow: 0 0 0 1px #a7f3d0;
}

.fb-detail-btn.fb-no.active {
  background: #fee2e2;
  border-color: #ef4444;
  box-shadow: 0 0 0 1px #fecaca;
}

.fb-detail-btn:hover {
  background: #f3f4f6;
  transform: translateY(-1px);
}

.fb-detail-status {
  margin: 0;
  font-size: 0.78rem;
}

.fb-detail-status.pending {
  color: #6b7280;
}

.fb-detail-status.answered {
  color: #374151;
}

.mapa {
  width: 100%;
  height: 380px;
  border-radius: 12px;
  margin-top: 2rem;
  box-shadow: 0 3px 8px rgba(0,0,0,.2);
}

.btn-volver {
  display: inline-block;
  margin-top: 2rem;
  padding: 0.5rem 1rem;
  background: #3b82f6;
  color: #fff;
  border-radius: 8px;
  text-decoration: none;
  font-size: 0.95rem;
}

.btn-volver:hover {
  background: #2563eb;
}

@media (min-width: 768px) {
  .detalle-wrapper {
    flex-direction: row;
    align-items: flex-start;
  }

  .detalle-imagenes {
    flex: 0 0 360px;
  }
}

.det-meta {
  margin: .5rem 0 1rem;
}

.meta-row {
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:1rem;
}

.badge-id {
  background:#e5f0ff;
  color:#1f3b78;
  padding:.25rem .5rem;
  border-radius:.5rem;
  font-weight:700;
  font-size: 0.9rem;
}

.img-box.placeholder .img-ph {
  width:100%;
  max-width:400px;
  height:220px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:12px;
  background:#f3f4f6;
  color:#6b7280;
  font-weight:600;
  border:1px dashed #cbd5e1;
}

.hint-muted {
  margin-top:1rem;
  color:#64748b;
  font-size: 0.9rem;
}

/* ============================
   Detalle de especie
   ============================ */

.detalle-especie {
  padding: 4rem 1rem;
  font-family: 'Nunito Sans', sans-serif;
}

/* Título y subtítulo */
.titulo-especie {
  font-size: 2.3rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
  color: #2b2b2b;
}

.subtitulo-especie {
  font-size: 1.1rem;
  color: #666;
  margin-bottom: 1.5rem;
}

.subtitulo-especie .latin {
  font-style: italic;
  color: #444;
}

/* Layout principal: imagen izquierda, texto derecha en desktop */
.especie-layout {
  display: grid;
  grid-template-columns: minmax(260px, 320px) minmax(0, 1fr);
  gap: 2rem;
  align-items: flex-start;
}

/* Imagen */
.especie-imagen img {
  width: 100%;
  max-height: 420px;
  object-fit: cover;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  display: block;
}

/* Texto / descripción */
.especie-texto .descripcion {
  font-size: 1.05rem;
  color: #444;
  line-height: 1.7;
  text-align: justify;
}

/* Breadcrumb genérico EcoLens */
.breadcrumb {
  background: #f1f3f5;
  padding: 0.75rem 1rem;
  font-size: 0.95rem;
  border-radius: 6px;
  color: #555;
  margin-bottom: 1.5rem;
  display: flex;
  flex-wrap: wrap;
  gap: 0.4rem;
}

.breadcrumb a {
  color: #2e7d32;
  text-decoration: none;
  font-weight: 500;
}
</style>
