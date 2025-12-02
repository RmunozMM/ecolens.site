<?php
/**
 * @var string   $phpVersion
 * @var string   $yiiVersion
 * @var string   $mysqlVersion
 * @var string   $appEnv
 * @var string   $appDebug
 * @var int      $diskTotal
 * @var int      $diskFree
 * @var float    $memUsageMb
 * @var string   $loadAvg
 * @var string   $uptimeHuman
 * @var int|null $dbSizeBytes
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Monitoreo | Sistema';

// Conversión de tamaños
$diskTotalGb = $diskTotal ? $diskTotal / (1024**3) : 0;
$diskFreeGb  = $diskFree  ? $diskFree  / (1024**3) : 0;
$diskUsedGb  = max($diskTotalGb - $diskFreeGb, 0);
$diskUsedPct = $diskTotalGb > 0 ? ($diskUsedGb / $diskTotalGb) * 100 : 0;

$dbSizeMb = $dbSizeBytes ? $dbSizeBytes / (1024**2) : null;
?>
<h1>Dashboard de Monitoreo y Telemetría</h1>

<p class="section-nav">
  Sección:
  <a href="<?= Url::to(['monitoreo/index']) ?>"   class="pill">Detecciones</a>
  <a href="<?= Url::to(['monitoreo/usuarios']) ?>" class="pill">Usuarios</a>
  <a href="<?= Url::to(['monitoreo/sistema']) ?>"  class="pill active">Sistema</a>
  <a href="<?= Url::to(['monitoreo/api']) ?>"      class="pill">API</a>
</p>

<p class="subtitle">
  Estado del entorno de ejecución (VPS / PHP / Base de Datos).
</p>

<div class="stats-grid">
  <div class="stat-item">
    <h3><?= Html::encode($phpVersion) ?></h3>
    <p>Versión de PHP</p>
  </div>
  <div class="stat-item">
    <h3><?= Html::encode($yiiVersion) ?></h3>
    <p>Versión de Yii</p>
  </div>
  <div class="stat-item">
    <h3><?= Html::encode($mysqlVersion) ?></h3>
    <p>Versión de MySQL</p>
  </div>
  <div class="stat-item">
    <h3><?= Html::encode(strtoupper($appEnv)) ?> / <?= Html::encode($appDebug) ?></h3>
    <p>Entorno / Debug</p>
  </div>
</div>

<div class="system-grid">
  <div class="sys-card">
    <h2>Disco</h2>
    <p>
      Usado:
      <strong><?= number_format($diskUsedGb, 2) ?> GB</strong> de
      <strong><?= number_format($diskTotalGb, 2) ?> GB</strong>
      (<?= number_format($diskUsedPct, 1) ?>%)
    </p>
    <div class="progress">
      <div class="progress-bar" style="width: <?= max(0, min(100, $diskUsedPct)) ?>%;"></div>
    </div>
    <p class="muted">Libre: <?= number_format($diskFreeGb, 2) ?> GB</p>
  </div>

  <div class="sys-card">
    <h2>Memoria & Carga</h2>
    <p><strong><?= number_format($memUsageMb, 2) ?> MB</strong> usados por PHP</p>
    <p><strong>Carga promedio:</strong> <?= Html::encode($loadAvg) ?></p>
    <p><strong>Uptime servidor:</strong> <?= Html::encode($uptimeHuman) ?></p>
  </div>

  <div class="sys-card">
    <h2>Base de Datos</h2>
    <?php if ($dbSizeMb !== null): ?>
      <p>Tamaño aproximado:</p>
      <p><strong><?= number_format($dbSizeMb, 2) ?> MB</strong></p>
    <?php else: ?>
      <p>No fue posible consultar el tamaño de la base de datos.</p>
    <?php endif; ?>
    <p class="muted">Información obtenida desde <code>information_schema.tables</code>.</p>
  </div>
</div>

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

.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin:1rem 0 0}
.stat-item{background:#fff;border-radius:12px;box-shadow:0 4px 8px rgba(0,0,0,.06);text-align:center;padding:1rem}
.stat-item h3{color:#0f172a;font-size:1.3rem;margin:0}
.stat-item p{color:#475569;margin:.3rem 0 0}

.system-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
  gap:1.25rem;
  margin-top:1.25rem;
}
.sys-card{
  background:#fff;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.05);
  padding:1rem 1.2rem;
}
.sys-card h2{font-size:1.1rem;margin-bottom:.75rem;color:#0f172a}
.sys-card p{margin:.2rem 0;color:#334155}
.sys-card .muted{font-size:.85rem;color:#94a3b8}

.progress{
  width:100%;height:12px;border-radius:999px;
  background:#e5e7eb;overflow:hidden;margin:.4rem 0 .2rem;
}
.progress-bar{
  height:100%;background:#22c55e;
  box-shadow:0 0 6px rgba(34,197,94,.6) inset;
}
</style>
