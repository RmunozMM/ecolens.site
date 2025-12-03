<?php
/** @var int $id */

$this->title = "Detalle de Detección #{$id}";
?>

<section class="detalle-deteccion container">
  <h1>Detalle de detección</h1>
  <div id="det-meta" class="det-meta"></div>
  <div id="det-content">Cargando...</div>
  <div id="det-error" style="display:none;color:#b91c1c;margin-top:1rem;"></div>
</section>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", async () => {
  const DET_ID = <?= json_encode($id) ?>;

  const isLocal = location.hostname.includes("localhost") || location.hostname.includes("127.0.0.1");
  const API_BASE = isLocal
    ? "http://localhost:8888/ecolens.site/panel-admin/web"
    : "https://ecolens.site/panel-admin/web";

  const API_URL = `${API_BASE}/api/deteccion/detalle?id=${DET_ID}`;

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

  try {
    const r = await fetch(API_URL, { credentials: "include" });
    const raw = await r.json();

    const success = (raw && typeof raw === "object" && "success" in raw)
      ? !!raw.success
      : true;

    if (!success) {
      err.textContent = raw.message || "No se pudo obtener la detección.";
      err.style.display = "block";
      cont.style.display = "none";
      return;
    }

    const d = (raw && typeof raw === "object" && "data" in raw)
      ? raw.data
      : raw;

    const e = d.especie    || {};
    const t = d.taxonomia  || {};
    const o = d.observador || {};
    const imgDeteccion = d.imagen_deteccion || "";
    const imgEspecie   = d.imagen_especie || e.imagen || "";

    console.log("DETALLE DETECCION", d);

    const especieDisplayName =
      e.nombre_comun || e.nombre_cientifico || "Especie desconocida";

    const especieUrl =
      d.url_especie
      || ((t && t.slug && e && e.slug)
            ? `https://ecolens.site/sitio/web/taxonomias/${t.slug}/${e.slug}`
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
        <div class="meta-right">
          <button id="copy-link" class="btn-copy" type="button" title="Copiar enlace al detalle">
            Copiar enlace
          </button>
        </div>
      </div>
    `;

    document.getElementById("copy-link").addEventListener("click", () => {
      const url = `${location.origin}${location.pathname}`;
      navigator.clipboard?.writeText(url).catch(() => {});
    });

    const grupoLabel = t?.nombre ? t.nombre : "No registrado";
    const grupoHtml = (t?.slug && t?.nombre)
      ? `<a href="https://ecolens.site/sitio/web/taxonomias/${t.slug}" rel="noopener">${t.nombre}</a>`
      : grupoLabel;

    const confRouter  = toPercent(pickField(d, "conf_router", "det_confianza_router"));
    const confExperto = toPercent(pickField(d, "conf_experto", "det_confianza_experto"));

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

      <a href="../../mis-detecciones" class="btn-volver">← Volver</a>
    `;

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
  flex-wrap: wrap;
  gap: 1.5rem;
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
    flex-direction: column;
    gap: 1rem;
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

.btn-copy {
  padding:.25rem .6rem;
  border:1px solid #d1d5db;
  border-radius:.5rem;
  background:#fff;
  cursor:pointer;
  font-size: 0.85rem;
}

.btn-copy:hover {
  background:#f3f4f6;
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
</style>
