<?php
session_start();
include __DIR__ . '/assets/config.php';    // Define $progressPercentage, $currentStep…
include __DIR__ . '/assets/conexion.php';  // Crea $conn como instancia mysqli

// 1) Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['options'] as $name => $value) {
        // Normalizar booleanos
        if (in_array($value, ['on','yes','1'], true)) {
            $value = 'yes';
        } elseif ($value === 'off' || $value === 'no' || $value === '') {
            $value = 'no';
        }
        $stmt = $conn->prepare("
            UPDATE opciones
            SET opc_valor = ?, updated_at = CURRENT_TIMESTAMP
            WHERE opc_nombre = ?
        ");
        $stmt->bind_param('ss', $value, $name);
        $stmt->execute();
    }
    header('Location: step7.php');
    exit;
}

// 2) Opciones para los combobox del sitio (cat_id = 2)
$comboValues = [
    'sitio_layout' => ['Personal','Minimal','Corporate'],
    'idioma_sitio' => ['es-ES','en-US','fr-FR','pt-BR'],
    'zona_horaria' => (function(){
        $tz = DateTimeZone::listIdentifiers();
        $out = [];
        foreach ($tz as $id) {
            $out[$id] = str_replace('_',' ',$id);
        }
        return $out;
    })(),
    // añade aquí más combos si los tienes...
];

// 3) Obtener opciones de sitio (cat_id = 2)
$sql = "SELECT opc_nombre, opc_valor, opc_tipo, opc_descripcion
        FROM opciones
        WHERE opc_cat_id = 2
        ORDER BY opc_id";
$res = $conn->query($sql);
$options = $res->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Instalador CMS - Paso 6</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="installer-container">
    <h1>🌐 Opciones del Sitio</h1>
    <hr>

    <!-- —— Menú de pasos —— -->
    <?php include __DIR__ . '/assets/wizard-menu.php'; ?>

    <!-- Progress Bar -->
    <div class="progress-bar">
      <div class="progress" style="width: <?= $progressPercentage ?>%;"></div>
      <div class="progress-label"><?= round($progressPercentage) ?>%</div>
    </div>

    <form method="POST">
      <?php foreach ($options as $opt):
        $name  = $opt['opc_nombre'];
        $value = $opt['opc_valor'];
        $type  = $opt['opc_tipo'];
        $desc  = $opt['opc_descripcion'];
      ?>
        <div class="option-row">
          <div class="option-label"><?= htmlspecialchars($name) ?></div>
          <div class="option-desc"><?= htmlspecialchars($desc) ?></div>
          <div class="option-input">
            <?php if (isset($comboValues[$name])): ?>
              <select name="options[<?= htmlspecialchars($name) ?>]">
                <?php foreach ($comboValues[$name] as $optVal => $optLabel):
                  $val = is_int($optVal) ? $optLabel : $optVal;
                ?>
                  <option 
                    value="<?= htmlspecialchars($val) ?>" 
                    <?= $val === $value ? 'selected' : '' ?>
                  >
                    <?= htmlspecialchars($optLabel) ?>
                  </option>
                <?php endforeach; ?>
              </select>

            <?php elseif ($type === 'bool'): ?>
              <input 
                type="checkbox" 
                name="options[<?= htmlspecialchars($name) ?>]" 
                <?= $value === 'yes' ? 'checked' : '' ?> 
              >

            <?php elseif ($type === 'color'): ?>
              <input 
                type="color" 
                name="options[<?= htmlspecialchars($name) ?>]" 
                value="<?= htmlspecialchars($value) ?>" 
              >

            <?php elseif ($type === 'json'): ?>
              <textarea 
                name="options[<?= htmlspecialchars($name) ?>]" 
                rows="4"
              ><?= htmlspecialchars($value) ?></textarea>

            <?php elseif (in_array($type, ['int','float'], true)): ?>
              <input 
                type="number" 
                name="options[<?= htmlspecialchars($name) ?>]" 
                value="<?= htmlspecialchars($value) ?>" 
                <?= $type === 'float' ? 'step="0.01"' : '' ?> 
              >

            <?php else: 
              $inputType = filter_var($value, FILTER_VALIDATE_URL) ? 'url' : 'text';
            ?>
              <input 
                type="<?= $inputType ?>" 
                name="options[<?= htmlspecialchars($name) ?>]" 
                value="<?= htmlspecialchars($value) ?>" 
              >

            <?php endif; ?>
          </div><!-- /.option-input -->
        </div><!-- /.option-row -->
      <?php endforeach; ?>

      <div class="actions">
        <a href="step5.php" class="btn-back">🔙 Volver</a>
        <button type="submit" class="btn-next">Guardar y Continuar 🚀</button>
      </div>
    </form>
  </div>

  <footer class="footer">
    Cápsula Tech © <?= date('Y') ?>
  </footer>
</body>
</html>
<?php $conn->close(); ?>
