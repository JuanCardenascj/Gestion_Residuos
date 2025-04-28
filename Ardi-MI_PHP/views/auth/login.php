<?php require_once '../layouts/header.php'; ?>

<h1>Gestión de Residuos</h1>

<div id="form-section">
  <h2>Iniciar Sesión</h2>
  <form method="POST" action="/login">
    <input type="email" name="email" placeholder="Correo electrónico" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    
    <div class="whatsapp-option">
      <input type="checkbox" id="whatsappNotification" name="whatsapp_notification">
      <label for="whatsappNotification">Recibir notificaciones por WhatsApp</label>
    </div>
    
    <button type="submit">Ingresar</button>
  </form>

  <div class="login-options">
    <a href="/recovery">¿Olvidaste tu contraseña?</a>
    <a href="/register">¿Todavía no tienes una cuenta? Regístrate</a>
  </div>
</div>

<?php require_once '../layouts/footer.php'; ?>