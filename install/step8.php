<?php
session_start();
include __DIR__ . '/assets/config.php';    // Define $wizardSteps, $stepKeys, $currentFile, $currentIndex, $progressPercentage
include __DIR__ . '/assets/conexion.php';  // Crea $conn como instancia mysqli

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modulos'])) {
    $modulosSeleccionados = $_POST['modulos'];
    $conn->query("UPDATE menu SET men_mostrar = 'No'");

    foreach ($modulosSeleccionados as $moduloId) {
        $conn->query("UPDATE menu SET men_mostrar = 'Si' WHERE men_id = " . intval($moduloId));
    }

    $success = "✅ Módulos habilitados correctamente.";
    // Redirect after a short pause
    header("refresh:2;url=step9.php");
}

$query = "SELECT * FROM menu WHERE men_nivel = 'nivel_1' ORDER BY men_id ASC";
$result = $conn->query($query);

$menus = [];
while ($menu = $result->fetch_assoc()) {
    $subQuery    = "SELECT * FROM menu WHERE men_padre_id = {$menu['men_id']} AND men_nivel = 'nivel_2'";
    $subResult   = $conn->query($subQuery);
    $menu['submenus'] = $subResult->fetch_all(MYSQLI_ASSOC);
    $menus[] = $menu;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Instalador CMS - Paso 8</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .module-card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .module-header {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }
        .submenu-container {
            background-color: #e9f5ff;
            padding: 10px;
            margin-top: 10px;
            border-left: 4px solid #007BFF;
            border-radius: 5px;
        }
        .submenu-item {
            margin-left: 10px;
        }
        .actions {
            margin: 15px 0;
            text-align: center;
        }
        label {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="installer-container">
        <h1>🗂️ Configuración de Módulos</h1>
        <hr>

        <!-- —— Menú de pasos —— -->
        <?php include __DIR__ . '/assets/wizard-menu.php'; ?>

        <!-- Progress Bar -->
        <div class="progress-bar">
            <div class="progress" style="width: <?= $progressPercentage ?>%;"></div>
            <div class="progress-label"><?= round($progressPercentage) ?>%</div>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div class="actions">
            <button type="button" class="btn-next" onclick="seleccionarTodo()">Seleccionar Todo</button>
            <button type="button" class="btn-back" onclick="deseleccionarTodo()">Deseleccionar Todo</button>
        </div>

        <form method="post">
            <?php foreach ($menus as $menu): ?>
                <div class="module-card">
                    <label class="module-header">
                        <input
                          type="checkbox"
                          class="nivel1"
                          name="modulos[]"
                          value="<?= $menu['men_id'] ?>"
                          <?= $menu['men_mostrar'] === 'Si' ? 'checked' : '' ?>
                          onchange="toggleSubmenus(<?= $menu['men_id'] ?>, this.checked)"
                        >
                        <?= htmlspecialchars($menu['men_nombre']) ?>
                    </label>

                    <?php if (!empty($menu['submenus'])): ?>
                        <div class="submenu-container">
                            <?php foreach ($menu['submenus'] as $subMenu): ?>
                                <label class="submenu-item">
                                    <input
                                      type="checkbox"
                                      class="nivel2"
                                      data-parent="<?= $menu['men_id'] ?>"
                                      name="modulos[]"
                                      value="<?= $subMenu['men_id'] ?>"
                                      <?= $subMenu['men_mostrar'] === 'Si' ? 'checked' : '' ?>
                                    >
                                    <?= htmlspecialchars($subMenu['men_nombre']) ?>
                                </label><br>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <div class="actions">
                <a href="step7.php" class="btn-back">🔙 Volver</a>
                <button type="submit" class="btn-next">Guardar y Continuar 🚀</button>
            </div>
        </form>
    </div>

    <footer class="footer">
        Cápsula Tech © <?= date('Y') ?>
    </footer>

    <script>
        function seleccionarTodo() {
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);
        }
        function deseleccionarTodo() {
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        }
        function toggleSubmenus(parentId, isChecked) {
            document
              .querySelectorAll(`input[data-parent='${parentId}']`)
              .forEach(sub => sub.checked = isChecked);
        }
    </script>
<?php $conn->close(); ?>
</body>
</html>
