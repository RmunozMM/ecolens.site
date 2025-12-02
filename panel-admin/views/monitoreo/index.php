<?php
/** @var string $rango */
/** @var string $desde */
/** @var int $totalDetecciones */
/** @var float $promedioPrecision */
/** @var int $latenciaPromedioMs */
/** @var array $latP */
/** @var array $serieDet */
/** @var bool $agrupaPorHora */
/** @var string $trl */
/** @var array $topEspecies */
/** @var array $precisionEspecie */
/** @var array $topObservadores */
/** @var array $ultimas */
/** @var array $geoPuntos */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Monitoreo | Panel";

/** Sección activa */
$actionId = $this->context->action->id ?? 'index';
?>
<h1>Dashboard de Monitoreo y Telemetría</h1>

<nav class="mon-nav">
  <span class="mon-nav-label">Sección:</span>

  <a href="<?= Url::to(['monitoreo/index', 'rango' => $rango]) ?>"
     class="mon-tab <?= $actionId === 'index' ? 'active' : '' ?>">
    Detecciones
  </a>

  <a href="<?= Url::to(['monitoreo/usuarios']) ?>"
     class="mon-tab <?= $actionId === 'usuarios' ? 'active' : '' ?>">
    Usuarios
  </a>

  <a href="<?= Url::to(['monitoreo/sistema']) ?>"
     class="mon-tab <?= $actionId === 'sistema' ? 'active' : '' ?>">
    Sistema
  </a>

  <a href="<?= Url::to(['monitoreo/api']) ?>"
     class="mon-tab <?= $actionId === 'api' ? 'active' : '' ?>">
    API
  </a>
</nav>

<p class="subtitle">
  Métricas internas del sistema · Rango:
  <a class="chip <?= $rango==='24h'?'active':'' ?>" href="?rango=24h">24h</a>
  <a class="chip <?= $rango==='7d'?'active':'' ?>"  href="?rango=7d">7 días</a>
  <a class="chip <?= $rango==='30d'?'active':'' ?>" href="?rango=30d">30 días</a>
  <a class="chip <?= $rango==='todo'?'active':'' ?>" href="?rango=todo">Todo</a>
  <span class="from">desde <?= Html::encode($desde) ?></span>
</p>

<!-- KPIs -->
<div class="stats-grid">
  <div class="stat-item">
    <h3><?= number_format((int)$totalDetecciones) ?></h3>
    <p>Detecciones en el rango</p>
  </div>
  <div class="stat-item">
    <h3><?= number_format($promedioPrecision * 100, 1) ?>%</h3>
    <p>Precisión promedio</p>
  </div>
  <div class="stat-item">
    <h3><?= (int)$latenciaPromedioMs ?> ms</h3>
    <p>Latencia promedio</p>
  </div>
  <div class="stat-item">
    <h3><?= Html::encode($trl) ?></h3>
    <p>Madurez tecnológica</p>
  </div>
</div>

<!-- Percentiles de latencia -->
<div class="p-grid">
  <div class="p-item"><span>P50</span><strong><?= (int)($latP['p50'] ?? 0) ?> ms</strong></div>
  <div class="p-item"><span>P90</span><strong><?= (int)($latP['p90'] ?? 0) ?> ms</strong></div>
  <div class="p-item"><span>P95</span><strong><?= (int)($latP['p95'] ?? 0) ?> ms</strong></div>
  <div class="p-item"><span>P99</span><strong><?= (int)($latP['p99'] ?? 0) ?> ms</strong></div>
</div>

<div class="chart-container-grid">
  <div class="chart-item">
    <h2>Detecciones en el tiempo (<?= $agrupaPorHora ? 'por hora' : 'por día' ?>)</h2>
    <canvas id="chart-serie"></canvas>
  </div>

  <div class="chart-item">
    <h2>Top 5 Especies Detectadas</h2>
    <canvas id="chart-top"></canvas>
  </div>

  <div class="chart-item">
    <h2>Precisión por Especie (n ≥ 5)</h2>
    <canvas id="chart-precision-especie"></canvas>
  </div>

  <div class="chart-item full-width">
    <h2>Distribución Geográfica de Detecciones</h2>
    <div id="mapa-detecciones" class="mapa"></div>
  </div>
</div>

<!-- Tablas -->
<div class="tables-grid">
  <div class="table-card">
    <h3>Top Observadores (por cantidad de detecciones)</h3>
    <table class="table">
      <thead><tr><th>#</th><th>Observador</th><th>Usuario</th><th>Detecciones</th></tr></thead>
      <tbody>
      <?php foreach ($topObservadores as $i=>$o): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><?= Html::encode($o['obs_nombre'] ?? '') ?></td>
          <td><?= Html::encode($o['obs_usuario'] ?? '') ?></td>
          <td class="num"><?= number_format((int)($o['conteo'] ?? 0)) ?></td>
        </tr>
      <?php endforeach; if (empty($topObservadores)): ?>
        <tr><td colspan="4" class="empty">Sin datos en el rango.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="table-card">
    <h3>Últimas detecciones</h3>
    <table class="table">
      <thead><tr>
        <th>ID</th><th>Fecha</th><th>Especie</th><th>Observador</th><th>Lat/Lng</th><th>Latencia</th>
      </tr></thead>
      <tbody>
      <?php foreach ($ultimas as $row): ?>
        <tr>
          <td class="num">
            <?= Html::a(
                (int)$row['det_id'],
                "https://ecolens.site/sitio/web/detalle-deteccion/" . (int)$row['det_id'],
                ['target' => '_blank', 'rel' => 'noopener']
            ) ?>
          </td>
          <td><?= Html::encode($row['det_fecha']) ?></td>
          <td><?= Html::encode($row['esp_nombre_comun'] ?? '—') ?></td>
          <td><?= Html::encode($row['obs_nombre'] ?? '—') ?></td>
          <td><?= Html::encode(($row['det_latitud'] ?? '—') . ', ' . ($row['det_longitud'] ?? '—')) ?></td>
          <td class="num"><?= (int)($row['det_tiempo_router_ms'] ?? 0) ?> ms</td>
        </tr>
      <?php endforeach; if (empty($ultimas)): ?>
        <tr><td colspan="6" class="empty">Sin actividades recientes.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Dependencias (panel) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
const SERIE_DET = <?= json_encode($serieDet,        JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;
const TOP       = <?= json_encode($topEspecies,     JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;
const PREC_ESP  = <?= json_encode($precisionEspecie,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;
const GEO       = <?= json_encode($geoPuntos,       JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;

document.addEventListener("DOMContentLoaded", () => {
  // Serie de detecciones
  const sLabels = SERIE_DET.map(x => x.bucket);
  const sValues = SERIE_DET.map(x => Number(x.c));
  new Chart(document.getElementById("chart-serie"), {
    type: "line",
    data: { labels: sLabels, datasets: [{ label: "Detecciones", data: sValues, borderColor: "#1f7a8c", backgroundColor: "rgba(31,122,140,.2)", fill: true, tension: .3 }] },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
  });

  // Top especies
  const tLabels = TOP.map(x => x.esp_nombre_comun || "Sin nombre");
  const tValues = TOP.map(x => Number(x.conteo));
  new Chart(document.getElementById("chart-top"), {
    type: "bar",
    data: { labels: tLabels, datasets: [{ label: "Detecciones", data: tValues, backgroundColor: "#22c55e" }] },
    options: { responsive: true, indexAxis: "y", scales: { x: { beginAtZero: true } } }
  });

  // Precisión por especie
  const pLabels = PREC_ESP.map(x => x.esp_nombre_comun || "Sin nombre");
  const pValues = PREC_ESP.map(x => Number(x.prom) * 100);
  new Chart(document.getElementById("chart-precision-especie"), {
    type: "bar",
    data: { labels: pLabels, datasets: [{ label: "Precisión (%)", data: pValues, backgroundColor: "#3b82f6" }] },
    options: { responsive: true, indexAxis: "y", scales: { x: { beginAtZero: true, max: 100 } } }
  });

  // Mapa
  const map = L.map("mapa-detecciones").setView([-33.45, -70.65], 11);
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", { attribution: "&copy; OpenStreetMap contributors" }).addTo(map);
  const bounds = [];
  GEO.forEach(p => {
    const lat = parseFloat(p.lat), lng = parseFloat(p.lng);
    if (Number.isFinite(lat) && Number.isFinite(lng)) {
      L.circleMarker([lat, lng], { radius: 5, color: "#2563eb", fillOpacity: 0.7 }).addTo(map);
      bounds.push([lat, lng]);
    }
  });
  if (bounds.length > 1) map.fitBounds(bounds, { padding: [20,20] });
});
</script>

<style>
.mon-nav{
  display:flex;
  align-items:center;
  flex-wrap:wrap;
  gap:.5rem;
  margin:.25rem 0 .75rem;
}
.mon-nav-label{
  color:#64748b;
  font-size:.9rem;
  margin-right:.25rem;
}
.mon-tab{
  padding:.25rem .8rem;
  border-radius:9999px;
  border:1px solid #e2e8f0;
  text-decoration:none;
  font-size:.9rem;
  color:#475569;
  background:#ffffff;
  transition:.12s ease-out;
}
.mon-tab:hover{
  background:#f1f5f9;
}
.mon-tab.active{
  background:#0f172a;
  color:#ffffff;
  border-color:#0f172a;
}

.subtitle{margin:.3rem 0 1rem;color:#64748b}
.subtitle .chip{display:inline-block;padding:.2rem .6rem;border-radius:9999px;background:#eef2f7;color:#334155;text-decoration:none;margin-left:.25rem;font-weight:600}
.subtitle .chip.active{background:#1f7a8c;color:#fff}
.subtitle .from{margin-left:.5rem;color:#94a3b8}

.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin:1rem 0 0}
.stat-item{background:#fff;border-radius:12px;box-shadow:0 4px 8px rgba(0,0,0,.06);text-align:center;padding:1rem}
.stat-item h3{color:#0f172a;font-size:1.8rem;margin:0}
.stat-item p{color:#475569;margin:.3rem 0 0}

.p-grid{display:grid;grid-template-columns:repeat(4,minmax(120px,1fr));gap:.75rem;margin:1rem 0}
.p-item{background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:.75rem;text-align:center}
.p-item span{display:block;color:#64748b;font-weight:700}
.p-item strong{display:block;margin-top:.25rem;color:#0f172a;font-size:1.1rem}

.chart-container-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(380px,1fr));gap:1.25rem;margin-top:1rem}
.chart-item{background:#fff;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.05);padding:1rem}
.full-width{grid-column:1/-1}
.mapa{height:420px;border-radius:8px;margin-top:1rem}

.tables-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(420px,1fr));gap:1.25rem;margin-top:1.25rem}
.table-card{background:#fff;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.05);padding:1rem}
.table{width:100%;border-collapse:collapse}
.table th,.table td{padding:.55rem .6rem;border-bottom:1px solid #e2e8f0;font-size:.95rem}
.table th{color:#475569;text-align:left}
.table td.num{text-align:right}
.table .empty{color:#94a3b8;text-align:center}
</style>
