<?php
// Vista: Dashboard de Monitoreo y Telemetría (usuario logueado)

$this->title = "Mi actividad en EcoLens";

/**
 * Carga de entorno robusta (funciona aunque el archivo no exista)
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
    $prefix = $isLocal ? '/ecolens.site' : '';
    return [
        'isLocal'   => $isLocal,
        'API_BASE'  => ($isLocal ? 'http://localhost:8888' : 'https://ecolens.site') . $prefix . '/panel-admin/web',
        'SITE_BASE' => ($isLocal ? 'http://localhost:8888' : 'https://ecolens.site') . $prefix . '/sitio/web',
        'endpoints' => [],
    ];
}
$env = ecolens_env_load();
$API_BASE = rtrim($env['API_BASE'], '/');

// Endpoint de métricas del usuario
// Si lo defines en el env como endpoints['metrics_user'], lo toma de ahí
$API_METRICA = $env['endpoints']['metrics_user'] ?? ($API_BASE . '/api/monitoreo/metrica-usuario');
?>

<section class="monitoreo-section container">
  <h1>Mi actividad en EcoLens</h1>
  <p class="subtitle">
    Resumen de tus detecciones, especies identificadas y desempeño de la IA basado en tu propio uso de la plataforma.
  </p>

  <div id="stats-grid" class="stats-grid"></div>

  <div class="chart-container-grid">
    <div class="chart-item">
      <h2>Latencia de tus detecciones (últimas 24h)</h2>
      <canvas id="chart-latencia"></canvas>
    </div>
    <div class="chart-item">
      <h2>Top 5 especies que has detectado</h2>
      <canvas id="chart-top"></canvas>
    </div>
    <div class="chart-item full-width">
      <h2>Mapa de tus detecciones</h2>
      <div id="mapa-detecciones" class="mapa"></div>
    </div>
  </div>
</section>

<!-- Dependencias -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", async () => {
  const API_URL = <?= json_encode($API_METRICA, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;

  async function cargarMetrica() {
    try {
      const r   = await fetch(API_URL, { credentials: "include" });
      const txt = await r.text();
      let data;
      try { data = JSON.parse(txt); }
      catch {
        console.error("Respuesta no JSON de métricas:", txt);
        throw new Error("El servidor no devolvió JSON.");
      }

      if (!data.success) {
        console.error("Error de API:", data.message || "Error desconocido");
        const grid = document.getElementById("stats-grid");
        grid.innerHTML = `
          <div class="stat-item">
            <h3>Sin datos</h3>
            <p>${data.message || "No fue posible cargar tus métricas. Asegúrate de haber iniciado sesión."}</p>
          </div>
        `;
        return;
      }

      // === Indicadores ===
      const grid = document.getElementById("stats-grid");
      const promPrec = typeof data.promedio_precision === "number" ? (data.promedio_precision * 100).toFixed(1) : "—";
      const latProm = typeof data.latencia_promedio_ms === "number" ? data.latencia_promedio_ms.toFixed(0) : "—";

      const totalDet = data.total_detecciones ?? 0;
      const textoDet = totalDet > 0
        ? "Detecciones realizadas con EcoLens"
        : "Aún no registras detecciones. Sube tu primera imagen para comenzar a ver estadísticas.";

      grid.innerHTML = `
        <div class="stat-item">
          <h3>${totalDet}</h3>
          <p>${textoDet}</p>
        </div>
        <div class="stat-item">
          <h3>${promPrec}%</h3>
          <p>Precisión promedio de la IA en tus detecciones</p>
        </div>
        <div class="stat-item">
          <h3>${latProm} ms</h3>
          <p>Latencia promedio de procesamiento</p>
        </div>
        <div class="stat-item">
          <h3>${data.trl ?? "—"}</h3>
          <p>Nivel de madurez tecnológica del sistema</p>
        </div>
      `;

      // === Gráfico: Latencia ===
      const latLabels = Array.isArray(data.latencia_24h) ? data.latencia_24h.map(x => `${x.hora}:00`) : [];
      const latValues = Array.isArray(data.latencia_24h) ? data.latencia_24h.map(x => x.valor) : [];

      if (latLabels.length > 0) {
        new Chart(document.getElementById("chart-latencia"), {
          type: "line",
          data: {
            labels: latLabels,
            datasets: [{
              label: "Latencia promedio (ms)",
              data: latValues,
              borderColor: "#3b82f6",
              backgroundColor: "rgba(59,130,246,0.2)",
              fill: true,
              tension: 0.3
            }]
          },
          options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
      }

      // === Gráfico: Top Especies ===
      const topLabels = Array.isArray(data.top_especies)
        ? data.top_especies.map(x => x.esp_nombre_comun || "Sin nombre")
        : [];
      const topValues = Array.isArray(data.top_especies)
        ? data.top_especies.map(x => x.conteo)
        : [];

      if (topLabels.length > 0) {
        new Chart(document.getElementById("chart-top"), {
          type: "bar",
          data: {
            labels: topLabels,
            datasets: [{
              label: "Detecciones",
              data: topValues,
              backgroundColor: "#22c55e"
            }]
          },
          options: { responsive: true, indexAxis: "y" }
        });
      }

      // === Mapa: Detecciones ===
      const map = L.map("mapa-detecciones").setView([-33.45, -70.65], 11);
      L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors"
      }).addTo(map);

      const puntos = Array.isArray(data.geo_puntos) ? data.geo_puntos : [];
      const bounds = [];
      puntos.forEach(p => {
        const lat = parseFloat(p.lat), lng = parseFloat(p.lng);
        if (Number.isFinite(lat) && Number.isFinite(lng)) {
          L.circleMarker([lat, lng], { radius: 5, color: "#2563eb", fillOpacity: 0.7 }).addTo(map);
          bounds.push([lat, lng]);
        }
      });
      if (bounds.length > 1) {
        map.fitBounds(bounds, { padding: [20, 20] });
      }

    } catch (err) {
      console.error("❌ Error al cargar métricas:", err);
      const grid = document.getElementById("stats-grid");
      grid.innerHTML = `
        <div class="stat-item">
          <h3>Error</h3>
          <p>No fue posible cargar tus métricas en este momento.</p>
        </div>
      `;
    }
  }

  // Cargar una vez y actualizar cada 60 segundos
  await cargarMetrica();
  setInterval(cargarMetrica, 60000);
});
</script>

<style>
.monitoreo-section {
  padding: 2rem 1rem;
  font-family: "Nunito Sans", sans-serif;
}
.subtitle { margin-top: .3rem; color: #64748b; }
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem; margin: 1.5rem 0;
}
.stat-item {
  background: #fff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,.08);
  text-align: center; padding: 1rem;
}
.stat-item h3 { color: #1e293b; font-size: 1.8rem; margin: 0; }
.stat-item p { color: #475569; margin: .3rem 0 0; }
.chart-container-grid {
  display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 1.5rem;
}
.chart-item { background: #fff; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,.05); padding: 1rem; }
.full-width { grid-column: 1 / -1; }
.mapa { height: 400px; border-radius: 8px; margin-top: 1rem; }
</style>
