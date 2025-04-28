<?php
define('APP_NAME', 'ARDI-MI ♻️');
define('APP_VERSION', '1.0');
define('WHATSAPP_NOTIFICATIONS_ENABLED', true);

// Tasas de puntos por tipo de residuo
define('POINTS_RATES', [
  'organico' => 0.8,
  'inorganico' => 0.5,
  'reciclable' => 1,
  'peligroso' => 2
]);

// Roles de usuario
define('USER_ROLES', [
  'user' => 'Usuario',
  'company' => 'Empresa Recolectora',
  'admin' => 'Administrador'
]);

// Tipos de residuos
define('WASTE_TYPES', [
  'organico' => 'Orgánico 🍂',
  'inorganico' => 'Inorgánico 🏗️',
  'reciclable' => 'Reciclable ♻️',
  'peligroso' => 'Peligroso ☣️'
]);

// Tipos de vehículos
define('VEHICLE_TYPES', [
  'compacto' => 'Compacto',
  'mediano' => 'Mediano',
  'grande' => 'Grande',
  'especial' => 'Especial (peligrosos)'
]);
?>