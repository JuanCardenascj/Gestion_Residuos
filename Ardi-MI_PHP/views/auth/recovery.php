<?php require_once '../layouts/header.php'; ?>

<h1>Recuperar Contraseña</h1>

<div id="form-section">
  <h2>Recuperar Contraseña</h2>
  <form method="POST" action="/recovery">
    <input type="email" name="email" placeholder="Correo electrónico" required>
    <button type="submit">Enviar Enlace</button>
  </form>
  
  <button class="logout-btn" onclick="window.location.href='/login'">Volver al Login</button>
</div>

<?php require_once '../layouts/footer.php'; ?>