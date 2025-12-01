<?php
session_start();
include __DIR__ . '/assets/config.php';  // Define $wizardSteps, $stepKeys, $currentFile, $currentStep, $progressPercentage

error_reporting(E_ALL);
ini_set('display_errors', 1);

$errors       = [];
$show_confirm = false;
$db_exists    = false;

// Valor por defecto
$entorno = $_POST['entorno'] ?? 'desarrollo';
$host    = '';
$db      = '';
$user    = '';
$pass    = '';

// 1) Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Volver al paso anterior
    if (isset($_POST['cancel'])) {
        header('Location: step1.php');
        exit;
    }

    // Leer credenciales según entorno
    if ($entorno === 'desarrollo') {
        $host = $_POST['local_host'];
        $db   = $_POST['local_db'];
        $user = $_POST['local_user'];
        $pass = $_POST['local_pass'];
    } else {
        $host = $_POST['prod_host'];
        $db   = $_POST['prod_db'];
        $user = $_POST['prod_user'];
        $pass = $_POST['prod_pass'];
    }

    // Intentar conexión
    $conn = @new mysqli($host, $user, $pass);
    if ($conn->connect_error) {
        $errors[] = "❌ Error de conexión ({$entorno}): " . $conn->connect_error;
    } else {
        // ¿Existe la BD?
        $db_exists = $conn
            ->query("SHOW DATABASES LIKE '{$conn->real_escape_string($db)}'")
            ->num_rows > 0;

        if ($db_exists && !isset($_POST['confirm_overwrite'])) {
            // Pedir confirmación
            $show_confirm = true;
        } elseif ($db_exists && isset($_POST['confirm_overwrite'])) {
            // Borrar para recrear en el siguiente paso
            $conn->query("DROP DATABASE `{$conn->real_escape_string($db)}`");
        }
    }

    // Si no hay errores y no estamos pidiendo confirmación, escribimos config y redirigimos
    if (empty($errors) && !$show_confirm) {
        // Generar db_resources.php
        $cfg  = "<?php\n";
        $cfg .= "\$arreglo = [\n";
        $cfg .= "    'class'    => 'yii\\\\db\\\\Connection',\n";
        $cfg .= "    'dsn'      => 'mysql:host={$host};dbname={$db}',\n";
        $cfg .= "    'username' => '{$user}',\n";
        $cfg .= "    'password' => '{$pass}',\n";
        $cfg .= "    'charset'  => 'utf8mb4',\n";
        $cfg .= "];\nreturn \$arreglo;\n";
        file_put_contents(__DIR__ . '/../recursos/db_resources.php', $cfg);

        // Guardar en sesión
        $_SESSION['db_config'] = compact('entorno','host','db','user','pass','db_exists');

        header('Location: step3.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Instalador CMS - Paso 2</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="installer-container">

    <h1>⚙️ Configuración de la Base de Datos</h1>

    <!-- —— Menú de pasos —— -->
    <?php include __DIR__ . '/assets/wizard-menu.php'; ?>

    <!-- Progress Bar -->
    <div class="progress-bar">
      <div class="progress" style="width: <?= $progressPercentage ?>%;"></div>
      <div class="progress-label"><?= round($progressPercentage) ?>%</div>
    </div>

    <!-- Mostrar errores de conexión -->
    <?php foreach ($errors as $err): ?>
      <div class="error-message"><?= htmlspecialchars($err) ?></div>
    <?php endforeach; ?>

    <?php if ($show_confirm): ?>
      <!-- Confirmación de sobreescritura -->
      <div class="warning-message">
        ⚠️ La base de datos "<strong><?= htmlspecialchars($db) ?></strong>" ya existe en el servidor de <?= htmlspecialchars($entorno) ?>.
        <br>¿Deseas <strong>eliminarla</strong> y continuar?
      </div>
      <form method="POST">
        <?php foreach ($_POST as $k => $v):
          if (is_array($v)) continue;
        ?>
          <input type="hidden" name="<?= htmlspecialchars($k) ?>" value="<?= htmlspecialchars($v) ?>">
        <?php endforeach; ?>
        <input type="hidden" name="confirm_overwrite" value="1">
        <div class="actions">
          <button type="submit" class="btn-next">Confirmar y Continuar 🚀</button>
          <button type="submit" name="cancel" class="btn-cancel">Cancelar</button>
        </div>
      </form>

    <?php else: ?>
      <!-- Formulario principal -->
      <form method="POST">
        <!-- Selector de entorno -->
        <div class="option-row">
          <div class="option-label">Entorno</div>
          <div class="option-desc">Selecciona dónde instalar</div>
          <div class="option-input">
            <label style="margin-right:1rem">
              <input type="radio" name="entorno" value="desarrollo" <?= $entorno==='desarrollo'?'checked':'' ?>>
              Desarrollo
            </label>
            <label>
              <input type="radio" name="entorno" value="produccion" <?= $entorno==='produccion'?'checked':'' ?>>
              Producción
            </label>
          </div>
        </div>

        <!-- Campos Desarrollo -->
        <div id="desarrollo-section">
          <?php foreach (
            ['host'=>'Servidor BD (dev)', 'db'=>'Nombre BD (dev)', 'user'=>'Usuario BD (dev)', 'pass'=>'Clave BD (dev)']
            as $field => $label
          ): ?>
            <div class="option-row">
              <div class="option-label"><?= $field ?></div>
              <div class="option-desc"><?= htmlspecialchars($label) ?></div>
              <div class="option-input">
                <input
                  type="<?= $field==='pass'?'password':'text' ?>"
                  name="local_<?= $field ?>"
                  value="<?= htmlspecialchars($_POST["local_{$field}"] ?? '') ?>"
                  placeholder="<?= $field==='db'?'mi_base_datos':($field==='user'?'root':'localhost') ?>"
                  <?= $field!=='pass'?'required':'' ?>
                >
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Campos Producción -->
        <div id="produccion-section">
          <?php foreach (
            ['host'=>'Servidor BD (prod)', 'db'=>'Nombre BD (prod)', 'user'=>'Usuario BD (prod)', 'pass'=>'Clave BD (prod)']
            as $field => $label
          ): ?>
            <div class="option-row">
              <div class="option-label"><?= $field ?></div>
              <div class="option-desc"><?= htmlspecialchars($label) ?></div>
              <div class="option-input">
                <input
                  type="<?= $field==='pass'?'password':'text' ?>"
                  name="prod_<?= $field ?>"
                  value="<?= htmlspecialchars($_POST["prod_{$field}"] ?? '') ?>"
                  placeholder="<?= $field==='db'?'mi_base_produccion':($field==='user'?'usuario_produccion':'servidor_produccion') ?>"
                >
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Botones -->
        <div class="actions">
          <a href="step1.php" class="btn-back">🔙 Volver</a>
          <button type="submit" class="btn-next">Guardar y Continuar 🚀</button>
        </div>
      </form>
    <?php endif; ?>

  </div>

  <!-- Footer -->
  <footer class="footer">
    Cápsula Tech © <?= date('Y') ?>
  </footer>

  <script>
    // Mostrar/ocultar secciones según entorno
    function toggleEnv() {
      const env = document.querySelector('input[name="entorno"]:checked').value;
      document.getElementById('desarrollo-section').style.display = env === 'desarrollo' ? 'block' : 'none';
      document.getElementById('produccion-section').style.display = env === 'produccion' ? 'block' : 'none';
    }
    document.querySelectorAll('input[name="entorno"]').forEach(r => r.addEventListener('change', toggleEnv));
    toggleEnv();
  </script>
</body>
</html>
