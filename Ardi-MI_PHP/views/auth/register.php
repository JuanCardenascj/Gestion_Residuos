<?php require_once '../layouts/header.php'; ?>

<h1>Registro de Usuario</h1>

<div id="form-section">
  <h2>Registro de Usuario</h2>
  <form method="POST" action="/register">
    <input type="text" name="name" placeholder="Nombre completo" required>
    <input type="email" name="email" placeholder="Correo electrónico" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
    <input type="tel" name="phone" placeholder="Teléfono (para notificaciones)">
    
    <select name="role">
      <option value="user">Usuario</option>
      <option value="company">Empresa Recolectora</option>
    </select>
    
    <div class="whatsapp-option">
      <input type="checkbox" id="registerWhatsappNotification" name="whatsapp_notification" checked>
      <label for="registerWhatsappNotification">Recibir notificaciones por WhatsApp</label>
    </div>
    
    <button type="submit">Registrarse</button>
  </form>
  
  <button class="logout-btn" onclick="window.location.href='/login'">Volver al Login</button>
</div>

<?php require_once '../layouts/footer.php'; ?>