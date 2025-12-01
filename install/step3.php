<?php
session_start();
include __DIR__ . '/assets/config.php';  // define $wizardSteps, $stepKeys, $currentFile, $currentIndex, $progressPercentage, $archivo_base_datos
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1) Recuperar config de BD desde sesión
if (empty($_SESSION['db_config'])) {
    die('⚠️ Error crítico: no hay configuración de base de datos en sesión. Vuelve al Paso 2.');
}
$config    = $_SESSION['db_config'];
$entorno   = $config['entorno'];
$db_exists = $config['db_exists'] ?? false;
$host      = $config['host'];
$db        = $config['db'];
$user      = $config['user'];
$pass      = $config['pass'];

// 2) Importación
$error       = '';
$success     = '';
$table_count = 0;

if (! file_exists($archivo_base_datos)) {
    $error = "❌ No se encontró el archivo de estructura SQL en: {$archivo_base_datos}";
} else {
    $conn = @new mysqli($host, $user, $pass);
    if ($conn->connect_error) {
        $error = "❌ Error de conexión: " . $conn->connect_error;
    } else {
        if ($entorno === 'desarrollo' || ($entorno === 'produccion' && ! $db_exists)) {
            if ($conn->query("CREATE DATABASE IF NOT EXISTS `{$db}`")) {
                $conn->select_db($db);
                $sql = file_get_contents($archivo_base_datos);
                if ($conn->multi_query($sql)) {
                    do { $conn->next_result(); } while ($conn->more_results());
                    $result      = $conn->query("SHOW TABLES");
                    $table_count = $result->num_rows;
                    $success     = "✅ Base de datos creada e importada. Tablas: {$table_count}.";
                } else {
                    $error = "❌ Error al importar la base de datos: " . $conn->error;
                }
            } else {
                $error = "❌ Error al crear la base de datos: " . $conn->error;
            }
        } else {
            // BD ya existe: solo contar tablas
            if ($conn->select_db($db)) {
                $result      = $conn->query("SHOW TABLES");
                $table_count = $result->num_rows;
                $success     = "✅ Conexión exitosa. Tablas encontradas: {$table_count}.";
            } else {
                $error = "❌ No se pudo conectar a la base de datos existente `{$db}`.";
            }
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Instalador CMS - Paso 3</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .status-section {
      margin: 2rem 0;
      text-align: center;
    }
    .error-message {
      color: #c00;
      font-weight: bold;
      margin-bottom: 1rem;
      text-align: center;
    }
    .success-message {
      color: #155724;
      background: #d4edda;
      border: 1px solid #c3e6cb;
      display: inline-block;
      padding: 1rem 1.5rem;
      border-radius: 5px;
      margin-bottom: 1.5rem;
    }
    /* Si no lo tienes ya */
    .wizard-menu ol {
      list-style: none;
      padding: 0;
      margin: 0 0 1rem;
    }
    .wizard-menu li {
      margin: .25rem 0;
      font-weight: 500;
    }
    .wizard-menu li.complete { color: #28a745; }
    .wizard-menu li.current  { color: #007bff; font-weight: bold;}
  </style>
</head>
<body>
  <div class="installer-container">
    <h1>📊 Creación e Importación de la Base de Datos</h1>
    <hr>

    <!-- —— Menú de pasos —— -->
    <?php include __DIR__ . '/assets/wizard-menu.php'; ?>
    </nav>

    <!-- Barra de progreso -->
    <div class="progress-bar">
      <div class="progress" style="width: <?= $progressPercentage ?>%;"></div>
      <div class="progress-label"><?= round($progressPercentage) ?>%</div>
    </div>

    <div class="status-section">
      <?php if ($error): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <div class="actions">
          <a href="step2.php" class="btn-back">🔙 Volver a Configuración BD</a>
        </div>
      <?php else: ?>
        <p class="success-message"><?= htmlspecialchars($success) ?></p>
        <div class="actions">
          <a href="step4.php" class="btn-next">Continuar 🚀</a>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <footer class="footer">
    Cápsula Tech © <?= date('Y') ?>
  </footer>
</body>
</html>
