<?php
/**
 * @var array  $env
 * @var string $apiBase
 * @var string $siteBase
 * @var string|null $predictUrl
 * @var string|null $whoamiUrl
 * @var array $pingPredict  // ['ok'=>bool,'status'=>int,'time_ms'=>int]
 * @var array $pingWhoami   // idem
 * @var bool  $dbOk
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Monitoreo | API';

function pillClass(bool $ok): string {
    return $ok ? 'status-pill ok' : 'status-pill fail';
}
function pillText(bool $ok): string {
    return $ok ? 'Operativo' : 'Error/Timeout';
}
?>
<h1>Dashboard de Monitoreo y Telemetría</h1>

<p class="section-nav">
  Sección:
  <a href="<?= Url::to(['monitoreo/index']) ?>"   class="pill">Detecciones</a>
  <a href="<?= Url::to(['monitoreo/usuarios']) ?>" class="pill">Usuarios</a>
  <a href="<?= Url::to(['monitoreo/sistema']) ?>"  class="pill">Sistema</a>
  <a href="<?= Url::to(['monitoreo/api']) ?>"      class="pill active">API</a>
</p>

<p class="subtitle">
  Estado de servicios críticos (FastAPI de inferencia, API Yii2 y base de datos).
</p>

<div class="env-grid">
  <div class="env-card">
    <h2>Entornos configurados</h2>
    <p><strong>API_BASE:</strong> <code><?= Html::encode($apiBase ?: 'n/a') ?></code></p>
    <p><strong>SITE_BASE:</strong> <code><?= Html::encode($siteBase ?: 'n/a') ?></code></p>
    <p class="muted">Origen: <code>ecolens_env.php</code></p>
  </div>

  <div class="env-card">
    <h2>Base de Datos</h2>
    <p>
      Estado conexión:
      <span class="<?= pillClass($dbOk) ?>"><?= pillText($dbOk) ?></span>
    </p>
    <p class="muted">Chequeo rápido mediante <code>SELECT 1</code>.</p>
  </div>
</div>

<div class="service-grid">
  <div class="service-card">
    <h2>Servicio de Predicción (FastAPI)</h2>
    <p><strong>Endpoint:</strong><br>
      <code><?= Html::encode($predictUrl ?: 'no configurado') ?></code>
    </p>
    <p>
      Estado:
      <span class="<?= pillClass($pingPredict['ok'] ?? false) ?>">
        <?= pillText($pingPredict['ok'] ?? false) ?>
      </span>
    </p>
    <ul class="meta">
      <li>HTTP: <strong><?= (int)($pingPredict['status'] ?? 0) ?></strong></li>
      <li>Latencia: <strong><?= (int)($pingPredict['time_ms'] ?? 0) ?> ms</strong></li>
    </ul>
    <p class="muted">
      Se utiliza para clasificación de imágenes de fauna (router + experto).
    </p>
  </div>

  <div class="service-card">
    <h2>API Observador / Sesión (Yii2)</h2>
    <p><strong>Endpoint:</strong><br>
      <code><?= Html::encode($whoamiUrl ?: 'no configurado') ?></code>
    </p>
    <p>
      Estado:
      <span class="<?= pillClass($pingWhoami['ok'] ?? false) ?>">
        <?= pillText($pingWhoami['ok'] ?? false) ?>
      </span>
    </p>
    <ul class="meta">
      <li>HTTP: <strong><?= (int)($pingWhoami['status'] ?? 0) ?></strong></li>
      <li>Latencia: <strong><?= (int)($pingWhoami['time_ms'] ?? 0) ?> ms</strong></li>
    </ul>
    <p class="muted">
      Endpoint de verificación rápida de sesión para el frontend de observadores.
    </p>
  </div>
</div>

<div class="legend">
  <span class="status-pill ok">Operativo</span>
  <span class="status-pill fail">Error / Timeout</span>
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

.env-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
  gap:1.25rem;
  margin-top:1rem;
}
.env-card{
  background:#fff;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.05);
  padding:1rem 1.2rem;
}
.env-card h2{font-size:1.05rem;margin-bottom:.6rem;color:#0f172a}
.env-card p{margin:.2rem 0;color:#334155}
.env-card .muted{font-size:.85rem;color:#94a3b8}

.service-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
  gap:1.25rem;
  margin-top:1.25rem;
}
.service-card{
  background:#fff;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.05);
  padding:1rem 1.2rem;
}
.service-card h2{font-size:1.05rem;margin-bottom:.6rem;color:#0f172a}
.service-card p{margin:.2rem 0;color:#334155}
.service-card .muted{font-size:.85rem;color:#94a3b8}
.service-card .meta{list-style:none;padding:0;margin:.3rem 0 .5rem}
.service-card .meta li{font-size:.9rem;color:#475569}

.status-pill{
  display:inline-block;
  padding:.1rem .6rem;
  border-radius:999px;
  font-size:.8rem;
  font-weight:600;
}
.status-pill.ok{
  background:#dcfce7;
  color:#166534;
}
.status-pill.fail{
  background:#fee2e2;
  color:#b91c1c;
}

.legend{margin-top:1.25rem;font-size:.85rem;color:#64748b}
.legend .status-pill{margin-right:.5rem}
</style>
