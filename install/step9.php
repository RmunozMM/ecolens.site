<?php include(__DIR__ . '/assets/config.php');?>
<?php
// Crear el archivo de control para marcar la instalación como completa
$installControlPath = __DIR__ . '/control/installed.lock';

// Verifica si la carpeta 'control' existe, si no, la crea
if (!file_exists(__DIR__ . '/control')) {
    mkdir(__DIR__ . '/control', 0755, true);
}

// Crea el archivo 'installed.lock'
file_put_contents($installControlPath, "Instalación completada el: " . date("Y-m-d H:i:s"));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador CMS - Paso 8 (Finalización)</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="installer-container">
        <h1>🎉 ¡Instalación Completa!</h1>

        <!-- Progress Bar -->
        <div class="progress-bar">
            <div class="progress" style="width: <?= $progressPercentage ?>%;"></div>
            <div class="progress-label"><?= round($progressPercentage) ?>%</div>
        </div>

        <!-- Mensaje de éxito -->
        <div class="alert-success">
            ¡El CMS se ha instalado correctamente! Ahora puedes acceder al Panel de Administración y comenzar a configurar tu sitio web. 🚀
        </div>

        <!-- Botones centrados -->
        <div class="actions">
            <a href="../panel-admin" class="btn-next">Ir al Panel de Administración 🗂️</a>
            <a href="../sitio"        class="btn-secondary">Ir al Sitio Web 🌐</a>
        </div>


        <!-- Nota informativa -->
        <p class="install-note">
            Recuerda que las credenciales del Administrador fueron enviadas por correo electrónico.<br>
            Debe activar su cuenta antes de iniciar sesión.
        </p>
    </div>

    <!-- Footer -->
    <footer class="footer">
        Cápsula Tech © <?= date('Y') ?>
    </footer>
</body>
</html>
