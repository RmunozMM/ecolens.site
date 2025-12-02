<?php
/**
 * @var int   $totalUsuarios
 * @var int   $nuevos30d
 * @var int   $activos7d
 * @var array $crecimiento       // [{bucket:'2025-11-01 00:00:00', c: 3}, ...]
 * @var array $topPorDetecciones // [{obs_nombre, obs_usuario, detecciones}, ...]
 * @var string $desde
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Monitoreo | Usuarios';

// Cálculo simple de tasa de actividad (activos últimos 7d / total)
$engagement = ($totalUsuarios > 0)
    ? round(($activos7d / $totalUsuarios) * 100, 1)
    : 0.0;
?>
<h1>Dashboard de Monitoreo y Telemetría</h1>

<p class="section-nav">
  Sección:
  <a href="<?= Url::to(['monitoreo/index']) ?>"   class="pill">Detecciones</a>
  <a href="<?= Url::to(['monitoreo/usuarios']) ?>" class="pill active">Usuarios</a>
  <a href="<?= Url::to(['monitoreo/sistema']) ?>"  class="pill">Sistema</a>
  <a href="<?= Url::to(['monitoreo/api']) ?>"      class="pill">API</a>
</p>

<p class="subtitle">
  Métricas de uso de observadores · Ventana de análisis desde
  <strong><?= Html::encode($desde) ?></strong>
</p>

<!-- KPIs -->
<div class="stats-grid">
  <div class="stat-item">
    <h3><?= number_format((int)$totalUsuarios) ?></h3>
    <p>Usuarios registrados</p>
  </div>
  <div class="stat-item">
    <h3><?= number_format((int)$nuevos30d) ?></h3>
    <p>Nuevos en los últimos 30 días</p>
  </div>
  <div class="stat-item">
    <h3><?= number_format((int)$activos7d) ?></h3>
    <p>Usuarios activos últimos 7 días</p>
  </div>
  <div class="stat-item">
    <h3><?= number_format($engagement, 1) ?>%</h3>
    <p>Engagement (activos 7d / total)</p>
  </div>
</div>

<div class="chart-container-grid">
  <div class="chart-item">
    <h2>Alta de usuarios en el tiempo</h2>
    <canvas id="chart-crecimiento"></canvas>
  </div>

  <div class="chart-item">
    <h2>Top 10 observadores por detecciones</h2>
    <canvas id="chart-top-obs"></canvas>
  </div>
</div>

<div class="tables-grid">
  <div class="table-card full-width">
    <h3>Detalle Top Observadores</h3>
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Observador</th>
          <th>Usuario</th>
          <th class="num">Detecciones</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($topPorDetecciones as $i => $row): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= Html::encode($row['obs_nombre'] ?? '—') ?></td>
          <td><?= Html::encode($row['obs_usuario'] ?? '—') ?></td>
          <td class="num"><?= number_format((int)($row['detecciones'] ?? 0)) ?></td>
        </tr>
      <?php endforeach; if (empty($topPorDetecciones)): ?>
        <tr><td colspan="4" class="empty">Sin datos en el rango.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Dependencias -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const CREC = <?= json_encode($crecimiento,       JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;
const TOP  = <?= json_encode($topPorDetecciones, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;

document.addEventListener("DOMContentLoaded", () => {
  // Crecimiento diario de usuarios
  const cLabels = CREC.map(x => x.bucket);
  const cValues = CREC.map(x => Number(x.c));
  const ctxCrec = document.getElementById("chart-crecimiento");
  if (ctxCrec) {
    new Chart(ctxCrec, {
      type: "line",
      data: {
        labels: cLabels,
        datasets: [{
          label: "Usuarios nuevos",
          data: cValues,
          borderColor: "#0f766e",
          backgroundColor: "rgba(15,118,110,.15)",
          fill: true,
          tension: .25
        }]
      },
      options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
      }
    });
  }

  // Top observadores por detecciones
  const tLabels = TOP.map(x => x.obs_nombre || x.obs_usuario || "Sin nombre");
  const tValues = TOP.map(x => Number(x.detecciones));
  const ctxTop = document.getElementById("chart-top-obs");
  if (ctxTop) {
    new Chart(ctxTop, {
      type: "bar",
      data: {
        labels: tLabels,
        datasets: [{
          label: "Detecciones",
          data: tValues,
          backgroundColor: "#2563eb"
        }]
      },
      options: {
        responsive: true,
        indexAxis: "y",
        scales: { x: { beginAtZero: true } }
      }
    });
  }
});
</script>

<style>
.section-nav{margin:.25rem 0 .75rem;color:#64748b;font-size:.95rem}
.section-nav .pill{
  display:inline-block;padding:.25rem .8rem;border-radius:9999px;
  border:1px solid #e5e7eb;background:#f9fafb;color:#374151;
  text-decoration:none;margin-left:.35rem;font-weight:600;
}
.section-nav .pill.active{
  background:#0f172a;color:#fff;border-color:#0f172a;
}

.subtitle{margin:.3rem 0 1rem;color:#64748b}
.subtitle strong{color:#0f172a}

.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin:1rem 0 0}
.stat-item{background:#fff;border-radius:12px;box-shadow:0 4px 8px rgba(0,0,0,.06);text-align:center;padding:1rem}
.stat-item h3{color:#0f172a;font-size:1.8rem;margin:0}
.stat-item p{color:#475569;margin:.3rem 0 0}

.chart-container-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(380px,1fr));gap:1.25rem;margin-top:1rem}
.chart-item{background:#fff;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.05);padding:1rem}
.chart-item h2{font-size:1.1rem;margin-bottom:.75rem;color:#0f172a}

.tables-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(420px,1fr));gap:1.25rem;margin-top:1.25rem}
.table-card{background:#fff;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.05);padding:1rem}
.table-card.full-width{grid-column:1/-1}
.table{width:100%;border-collapse:collapse}
.table th,.table td{padding:.55rem .6rem;border-bottom:1px solid #e2e8f0;font-size:.95rem}
.table th{color:#475569;text-align:left}
.table td.num{text-align:right}
.table .empty{color:#94a3b8;text-align:center}
</style>
