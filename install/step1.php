<?php
session_start();
include __DIR__ . '/assets/config.php';  // Define $wizardSteps, $stepKeys, $currentStep, $progressPercentage

// 1) Comprobación de requisitos de sistema
$requirements = [
    'PHP >= 7.4'                    => version_compare(PHP_VERSION, '7.4', '>='),
    'Extensión PDO habilitada'      => extension_loaded('pdo'),
    'Driver pdo_mysql'              => in_array('mysql', PDO::getAvailableDrivers(), true),
    'Extensión MySQLi habilitada'   => extension_loaded('mysqli'),
    'Extensión mbstring habilitada' => extension_loaded('mbstring'),
    'Extensión openssl habilitada'  => extension_loaded('openssl'),
    'Extensión cURL habilitada'     => extension_loaded('curl'),
    'Extensión GD habilitada'       => extension_loaded('gd'),
    'Permisos escritura en /recursos'    => is_writable(__DIR__ . '/../recursos'),
    'Permisos escritura en /panel-admin' => is_writable(__DIR__ . '/../panel-admin'),
    'Permisos escritura en /sitio'        => is_writable(__DIR__ . '/../sitio'),
];
$allPassed = !in_array(false, $requirements, true);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Instalador CMS - Paso 1</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    /* —— Menú wizard —— */
    .wizard-menu ol {
      list-style: none;
      padding: 0;
      margin: 0 0 1rem;
    }
    .wizard-menu li {
      display: flex;
      align-items: center;
      margin-bottom: .5rem;
      font-weight: 500;
    }
    .wizard-menu li.complete { color: #28a745; }
    .wizard-menu li.current  { color: #007bff; font-weight: bold; }
    .wizard-menu li .icon    { width: 1.5em; display: inline-block; text-align: center; }
  </style>
</head>
<body>
  <div class="installer-container">
    <h1>🛠️ Requisitos del Sistema</h1>

    <!-- —— Menú de pasos —— -->
    <?php include __DIR__ . '/assets/wizard-menu.php'; ?>

    <!-- —— Progress bar —— -->
    <div class="progress-bar">
      <div class="progress" style="width: <?= $progressPercentage ?>%;"></div>
      <div class="progress-label"><?= round($progressPercentage) ?>%</div>
    </div>

    <!-- —— Lista de requisitos —— -->
    <div class="requirements-section">
      <ul>
        <?php foreach ($requirements as $req => $status):
          $icon  = $status ? '✅' : '❌';
          $cls   = $status ? 'success' : 'error';
        ?>
          <li class="<?= $cls ?>"><?= $icon ?> <?= htmlspecialchars($req) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- —— Botones —— -->
    <div class="actions">
      <?php if ($allPassed): ?>
        <a href="step2.php" class="btn-next">Continuar con Configuración de la Base de Datos 🚀</a>
      <?php else: ?>
        <p class="error-message">⚠️ Por favor, corrige los errores antes de continuar.</p>
        <a href="step1.php" class="btn-secondary">🔄 Reintentar</a>
      <?php endif; ?>
    </div>
  </div>

  <footer class="footer">
    Cápsula Tech © <?= date('Y') ?>
  </footer>
</body>
</html>
