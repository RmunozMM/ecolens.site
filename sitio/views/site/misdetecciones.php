<?php
use yii\helpers\Html;

// Título
if (!empty($pagina->pag_titulo ?? null)) {
    $this->title = $pagina->pag_titulo;
} else {
    $this->title = "Mis detecciones";
}

/**
 * Carga entorno compartido (funciona aunque el archivo no exista)
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
    // Fallback sensato
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $isLocal = (bool)preg_match('/^(localhost|127\.0\.0\.1)(:\d+)?$/i', $host);
    return [
        'isLocal'   => $isLocal,
        'API_BASE'  => ($isLocal ? 'http://localhost:8888' : 'https://ecolens.site') . '/panel-admin/web',
        'SITE_BASE' => ($isLocal ? 'http://localhost:8888' : 'https://ecolens.site') . '/sitio/web',
        'endpoints' => [],
    ];
}

$env = ecolens_env_load();
$API_BASE  = rtrim($env['API_BASE'], '/');
$SITE_BASE = rtrim($env['SITE_BASE'], '/');

// Endpoints
$API_LISTAR   = $env['endpoints']['listar']   ?? ($API_BASE . '/api/deteccion/listar');
$API_WHOAMI   = $env['endpoints']['whoami']   ?? ($API_BASE . '/api/observador/whoami');
$API_FEEDBACK = $env['endpoints']['feedback'] ?? ($API_BASE . '/api/deteccion/feedback');

// ID desde sesión
$observerId = (int)Yii::$app->session->get('observador_id', 0);
?>

<section class="gallery-section container">
  <h1>Mis detecciones</h1>
  <p>Estas son tus detecciones registradas con EcoLens.</p>

  <div id="det-controls" style="margin:.5rem 0 1rem">
    <button id="refreshBtn" class="btn-refresh">Actualizar</button>
  </div>

  <div id="det-grid" class="gallery-grid"></div>
  <div id="det-empty" style="display:none;margin-top:1rem">No hay detecciones para mostrar.</div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
  // Inyectados desde PHP
  const API_LIST     = <?= json_encode($API_LISTAR,   JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;
  const API_WHOAMI   = <?= json_encode($API_WHOAMI,   JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;
  const API_FEEDBACK = <?= json_encode($API_FEEDBACK, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;
  const SITE_BASE    = <?= json_encode($SITE_BASE,    JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;
  let   OBSERVER_ID  = <?= (int)$observerId ?>;

  const grid  = document.getElementById("det-grid");
  const empty = document.getElementById("det-empty");
  const btn   = document.getElementById("refreshBtn");

  // Si no hay ID de sesión, intenta recuperarlo desde la API whoami
  async function ensureObserverId() {
    if (OBSERVER_ID) return OBSERVER_ID;
    try {
      const resp = await fetch(API_WHOAMI, { credentials: "include" });
      const txt  = await resp.text();
      const who  = JSON.parse(txt);
      if (who && who.authenticated && who.id) {
        OBSERVER_ID = who.id;
      }
    } catch (_) {}
    return OBSERVER_ID;
  }

  function applyFeedbackState(wrapper, feedback) {
    if (!wrapper) return;
    const detId    = wrapper.dataset.detId;
    const likeBtn  = wrapper.querySelector(".fb-like");
    const disBtn   = wrapper.querySelector(".fb-dislike");
    const statusEl = document.getElementById("fb-status-" + detId);

    wrapper.dataset.feedback = feedback || "";

    if (likeBtn)  likeBtn.classList.remove("is-active");
    if (disBtn)   disBtn.classList.remove("is-active");

    let text = "Aún no respondes si esta identificación coincide.";
    let cls  = "feedback-status feedback-pending";

    if (feedback === "like") {
      if (likeBtn) likeBtn.classList.add("is-active");
      text = "Marcaste esta identificación como correcta.";
      cls  = "feedback-status feedback-answered";
    } else if (feedback === "dislike") {
      if (disBtn) disBtn.classList.add("is-active");
      text = "Marcaste esta identificación como incorrecta.";
      cls  = "feedback-status feedback-answered";
    }

    if (statusEl) {
      statusEl.textContent = text;
      statusEl.className = cls;
    }
  }

  async function cargar(page = 1, perPage = 24) {
    grid.innerHTML = "";
    empty.style.display = "none";

    const uid = await ensureObserverId();
    if (!uid) {
      empty.textContent = "Inicia sesión para ver tus detecciones.";
      empty.style.display = "block";
      return;
    }

    const url = new URL(API_LIST);
    url.searchParams.set("observer_id", String(uid));
    url.searchParams.set("page", String(page));
    url.searchParams.set("per_page", String(perPage));

    try {
      const r   = await fetch(url.toString(), { credentials: "include" });
      const raw = await r.text();
      let data;
      try { data = JSON.parse(raw); }
      catch {
        console.error("Respuesta no JSON de listar:", raw);
        throw new Error("El servidor no devolvió JSON válido.");
      }

      if (!data.success || !Array.isArray(data.items) || data.items.length === 0) {
        empty.textContent = "No hay detecciones registradas.";
        empty.style.display = "block";
        return;
      }

      for (const it of data.items) {
        const especie = it.especie || {};
        const tax     = especie.taxonomia || {};

        // feedback_usuario viene del API; si no existe, queda pendiente
        const feedback = (it.feedback_usuario === "like" || it.feedback_usuario === "dislike")
          ? it.feedback_usuario
          : "";

        // ✅ Corrección: limpieza de URL de imagen duplicada
        let imgSrc = it.imagen_deteccion || especie.imagen || "";
        imgSrc = imgSrc.replace(
          /https?:\/\/[^/]+\/[^/]+\//,
          match => match.endsWith("/recursos/") ? match : match.replace(/ecolens\.site\//, "")
        );

        if (!imgSrc) imgSrc = "https://picsum.photos/400/300?random";

        const titulo      = especie.nombre_comun || especie.nombre_cientifico || "Especie desconocida";
        const fecha       = it.fecha
          ? new Date(it.fecha).toLocaleString("es-CL", { dateStyle: "medium", timeStyle: "short" })
          : "—";
        const ubicacion   = it.ubicacion || "Ubicación no especificada";
        const taxon       = tax.nombre || "Sin clasificación";
        const descripcion = (especie.descripcion || "")
          .replace(/<[^>]+>/g, "")
          .slice(0, 180) + (especie.descripcion ? "..." : "");

        const card = document.createElement("div");
        card.className = "gallery-card";
        card.innerHTML = `
          <div class="card-image">
            <img src="${imgSrc}" alt="${titulo}">
          </div>
          <div class="card-content">
            <div class="card-header-row">
              <h3 class="card-title">${titulo}</h3>
              <div class="feedback-wrapper"
                   data-det-id="${it.id}"
                   data-feedback="${feedback}">
                <button class="fb-btn fb-like"
                        type="button"
                        title="Marcar como correcta">👍</button>
                <button class="fb-btn fb-dislike"
                        type="button"
                        title="Marcar como incorrecta">👎</button>
              </div>
            </div>
            <p id="fb-status-${it.id}" class="feedback-status"></p>

            <p class="taxon">${taxon}</p>
            <p class="descripcion">${descripcion}</p>
            <p class="fecha">${fecha}</p>
            <p class="ubicacion">${ubicacion}</p>
            <div class="card-footer">
              <a href="${SITE_BASE}/detalle-deteccion/${it.id}" class="btn-ver">🔍 Ver detalle</a>
              ${ it.url_especie
                  ? `<a href="${it.url_especie}" target="_blank" class="btn-ver btn-sec">🌐 Ficha especie</a>`
                  : "" }
            </div>
          </div>
        `;
        grid.appendChild(card);

        // Aplica estado inicial (pendiente / like / dislike)
        const wrapper = card.querySelector(".feedback-wrapper");
        applyFeedbackState(wrapper, feedback);
      }
    } catch (err) {
      console.error("❌ Error al cargar detecciones:", err);
      empty.textContent = "Error al cargar detecciones.";
      empty.style.display = "block";
    }
  }

  btn.addEventListener("click", () => cargar());

  // Manejo de clics en like / dislike (delegado)
  grid.addEventListener("click", async (ev) => {
    const btn = ev.target.closest(".fb-btn");
    if (!btn) return;

    const wrapper = btn.closest(".feedback-wrapper");
    if (!wrapper || wrapper.dataset.sending === "1") return;

    const detId    = wrapper.dataset.detId;
    const current  = wrapper.dataset.feedback || "";
    const isLike   = btn.classList.contains("fb-like");
    const next     = isLike ? (current === "like" ? "" : "like")
                            : (current === "dislike" ? "" : "dislike");

    wrapper.dataset.sending = "1";

    try {
      const resp = await fetch(API_FEEDBACK, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        credentials: "include",
        body: JSON.stringify({ id: detId, feedback: next })
      });
      const txt  = await resp.text();
      let data   = {};
      try { data = JSON.parse(txt); } catch (_) {}

      if (!resp.ok || !data.success) {
        throw new Error(data.message || "Error al guardar tu respuesta.");
      }

      applyFeedbackState(wrapper, next);
    } catch (err) {
      console.error("❌ Error feedback:", err);
      alert("No se pudo guardar tu respuesta. Intenta nuevamente.");
    } finally {
      wrapper.dataset.sending = "0";
    }
  });

  // Primera carga
  cargar();
});
</script>

<style>
.gallery-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.2rem;
}
.gallery-card {
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  display: flex;
  flex-direction: column;
  transition: transform .2s ease, box-shadow .2s ease;
}
.gallery-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 6px 18px rgba(0,0,0,0.15);
}
.card-image img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}
.card-content {
  padding: 1rem;
  font-family: "Nunito Sans", sans-serif;
}
.card-content h3 {
  margin: 0;
  font-size: 1.2rem;
  color: #1e293b;
}

/* título + feedback alineados */
.card-header-row {
  display: flex;
  align-items: center;
  gap: .5rem;
}
.card-title {
  flex: 1 1 auto;
}

/* Bloque de like / dislike */
.feedback-wrapper {
  display: flex;
  gap: .25rem;
  flex-shrink: 0;
  align-items: center;
}
.fb-btn {
  border: none;
  background: transparent;
  font-size: 1.1rem;
  cursor: pointer;
  padding: .15rem .35rem;
  border-radius: 999px;
  opacity: .45;
  transition:
    opacity .15s ease,
    transform .1s ease,
    background-color .15s ease,
    box-shadow .15s ease;
}
.fb-btn:hover {
  opacity: .9;
  transform: translateY(-1px);
}
.fb-like.is-active {
  opacity: 1;
  background-color: #dcfce7;
  box-shadow: 0 0 0 1px #a7f3d0;
}
.fb-dislike.is-active {
  opacity: 1;
  background-color: #fee2e2;
  box-shadow: 0 0 0 1px #fecaca;
}

/* Texto de estado de feedback */
.feedback-status {
  margin: .15rem 0 .5rem;
  font-size: .78rem;
}
.feedback-pending {
  color: #6b7280;
}
.feedback-answered {
  color: #4b5563;
}

.card-content .taxon {
  color: #475569;
  font-size: 0.95rem;
  margin: 0.3rem 0 0.5rem;
}
.card-content .descripcion {
  font-size: 0.9rem;
  color: #4b5563;
  line-height: 1.4;
}
.card-content .fecha, .card-content .ubicacion {
  font-size: 0.8rem;
  color: #6b7280;
  margin-top: 0.25rem;
}
.card-footer {
  margin-top: 0.8rem;
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}
.btn-ver {
  display: inline-block;
  padding: 0.35rem 0.7rem;
  background: #3b82f6;
  color: #fff;
  border-radius: 6px;
  text-decoration: none;
  font-size: 0.85rem;
  transition: background .2s ease;
}
.btn-ver:hover { background: #2563eb; }
.btn-ver.btn-sec { background: #64748b; }
.btn-ver.btn-sec:hover { background: #475569; }
.btn-refresh {
  padding: 0.4rem 0.9rem;
  border-radius: 8px;
  border: 1px solid #cbd5e1;
  background: #f8fafc;
  cursor: pointer;
}
.btn-refresh:hover { background: #e2e8f0; }
</style>
