<?php
session_start();
include __DIR__ . '/assets/config.php';    // Define $wizardSteps, $stepKeys, $currentFile, $currentIndex, $progressPercentage
include __DIR__ . '/assets/conexion.php';  // Crea $conn como instancia mysqli

$error   = "";
$success = "";

// Generador de claves
function generateRandomKey($length = 200) {
    return bin2hex(random_bytes($length / 2));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_user  = trim($_POST['admin_user']);
    $admin_email = trim($_POST['admin_email']);

    if (!$admin_user || !$admin_email) {
        $error = "Todos los campos son obligatorios.";
    } else {
        // Verificar duplicados
        $stmt = $conn->prepare("SELECT 1 FROM usuarios WHERE usu_username = ? OR usu_email = ?");
        $stmt->bind_param("ss", $admin_user, $admin_email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "El nombre de usuario o el correo electrónico ya están en uso.";
        } else {
            // Preparar inserción
            $admin_pass     = date("Y") . "_" . $admin_user;
            $hashed_pass    = crypt($admin_pass, 'salt123');
            $auth_key       = generateRandomKey();
            $access_token   = generateRandomKey();
            $usu_email_verificado = "SI";
            $activate       = "NO";
            $rol_id         = 2;
            $usu_letra      = 15;

            $ins = $conn->prepare("
                INSERT INTO usuarios
                  (usu_username, usu_email, usu_email_verificado,
                   usu_password, usu_authKey, usu_accessToken,
                   usu_activate, usu_rol_id, usu_letra)
                VALUES (?,?,?,?,?,?,?,?,?)
            ");
            $ins->bind_param(
              "sssssssii",
              $admin_user, $admin_email, $usu_email_verificado,
              $hashed_pass, $auth_key, $access_token,
              $activate, $rol_id, $usu_letra
            );

            if ($ins->execute()) {
                $user_id         = $ins->insert_id;
                $activation_link = "http://{$_SERVER['HTTP_HOST']}/user/confirmar?usu_id="
                                 . urlencode($user_id)
                                 . "&usu_authKey=" . urlencode($auth_key);

                // Enviar correo
                $subject = "Confirmación de Registro - Instalador CMS";
                $message = "
                    <h1>Confirmación de Registro</h1>
                    <p>Hola <strong>$admin_user</strong>, para completar tu registro haz clic en:</p>
                    <p><a href='$activation_link'>Confirmar Registro</a></p>
                    <p>Usuario: <strong>$admin_user</strong><br>
                    Contraseña temporal: <strong>$admin_pass</strong></p>
                ";
                $headers  = "From: admin@cms.com\r\n"
                          . "MIME-Version: 1.0\r\n"
                          . "Content-Type: text/html; charset=UTF-8\r\n";

                if (mail($admin_email, $subject, $message, $headers)) {
                    $success = "Usuario administrador creado. Se ha enviado confirmación a $admin_email.";
                    header("refresh:3;url=step5.php");
                } else {
                    $error = "Usuario creado, pero no se pudo enviar el correo de confirmación.";
                }
            } else {
                $error = "Error al crear el usuario: " . $ins->error;
            }
            $ins->close();
        }
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Instalador CMS - Paso 4</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .wizard-menu ol { list-style:none; padding:0; margin:0 0 1rem; }
    .wizard-menu li { margin:.25rem 0; font-weight:500; }
    .wizard-menu li.complete { color:#28a745; }
    .wizard-menu li.current  { color:#007bff; font-weight:bold; }
    .alert-error {
      background: #fdecea;
      color: #b71c1c;
      padding: 1rem;
      border-radius: 5px;
      margin-bottom: 1rem;
      text-align: center;
      font-weight: bold;
    }
    /* Password display */
    #password-display {
      margin:0;
      font-weight:bold;
      color:#007BFF;
    }
  </style>
</head>
<body>
  <div class="installer-container">
    <h1>👤 Configuración del Usuario Administrador</h1>
    <hr>

    <!-- —— Menú de pasos —— -->
    <?php include __DIR__ . '/assets/wizard-menu.php'; ?>

    <!-- Progress bar -->
    <div class="progress-bar">
      <div class="progress" style="width: <?= $progressPercentage ?>%;"></div>
      <div class="progress-label"><?= round($progressPercentage) ?>%</div>
    </div>

    <!-- Mensajes -->
    <?php if ($error): ?>
      <div class="alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="alert alert-success" style="text-align:center;"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- Formulario -->
    <form method="POST">
      <div class="option-row">
        <div class="option-label">Nombre de Usuario</div>
        <div class="option-desc"></div>
        <div class="option-input">
          <input type="text"
                 name="admin_user"
                 id="admin_user"
                 oninput="mostrarPassword()"
                 required>
        </div>
      </div>

      <div class="option-row">
        <div class="option-label">Correo Electrónico</div>
        <div class="option-desc"></div>
        <div class="option-input">
          <input type="email"
                 name="admin_email"
                 required>
        </div>
      </div>

      <div class="option-row">
        <div class="option-label">Contraseña</div>
        <div class="option-desc">🔑 (generada automáticamente)</div>
        <div class="option-input">
          <p id="password-display"></p>
        </div>
      </div>

      <div class="actions">
        <button type="submit" class="btn-next">Crear Usuario y Continuar 🚀</button>
      </div>
    </form>
  </div>

  <footer class="footer">
    Cápsula Tech © <?= date('Y') ?>
  </footer>

  <script>
    function mostrarPassword() {
      const u = document.getElementById('admin_user').value;
      document.getElementById('password-display').textContent =
        u ? `${new Date().getFullYear()}_${u}` : '';
    }
  </script>
</body>
</html>
