<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $whatsapp = isset($_POST['whatsapp']);
    
    if (login($email, $password)) {
        $_SESSION['user']['whatsapp_notifications'] = $whatsapp;
        
        // Actualizar preferencia de WhatsApp en la base de datos
        $stmt = $pdo->prepare("UPDATE users SET whatsapp_notifications = ? WHERE id = ?");
        $stmt->execute([$whatsapp ? 1 : 0, $_SESSION['user']['id']]);
        
        header('Location: ../dashboard.php');
        exit();
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= APP_NAME ?> - Login</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <div class="container" id="login-page">
    <div class="header">
      <img src="../assets/images/logo.png" alt="logo" class="logo">
      <span><?= APP_NAME ?></span>
    </div>
    <h1>Gestión de Residuos</h1>

    <div id="form-section">
      <h2>Iniciar Sesión</h2>
      
      <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>
      
      <form method="POST">
        <input type="email" name="email" placeholder="Correo electrónico" required />
        <input type="password" name="password" placeholder="Contraseña" required />
        
        <div class="whatsapp-option">
          <input type="checkbox" id="whatsappNotification" name="whatsapp" checked>
          <label for="whatsappNotification">Recibir notificaciones por WhatsApp</label>
        </div>
        
        <button type="submit">Ingresar</button>
      </form>

      <div class="login-options">
        <a href="../recovery.php">¿Olvidaste tu contraseña?</a>
        <a href="../register.php">¿Todavía no tienes una cuenta? Regístrate</a>
      </div>
    </div>
  </div>
</body>
</html>