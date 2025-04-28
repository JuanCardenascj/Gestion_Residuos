<?php
require_once 'conf/constante.php';
require_once 'confg/database.php';

session_start();

// Autocarga de clases
spl_autoload_register(function($class) {
  $paths = [
    'controllers/' . $class . '.php',
    'models/' . $class . '.php'
  ];
  
  foreach ($paths as $path) {
    if (file_exists($path)) {
      require_once $path;
      return;
    }
  }
});

// Obtener la URL solicitada
$url = $_GET['url'] ?? 'auth/login';
$urlParts = explode('/', rtrim($url, '/'));

// Determinar el controlador y método
$controllerName = ucfirst($urlParts[0]) . 'Controller';
$methodName = $urlParts[1] ?? 'index';
$params = array_slice($urlParts, 2);

// Verificar si el controlador existe
if (class_exists($controllerName)) {
  $controller = new $controllerName();
  
  // Verificar si el método existe
  if (method_exists($controller, $methodName)) {
    // Llamar al método con los parámetros
    call_user_func_array([$controller, $methodName], $params);
  } else {
    // Método no encontrado
    http_response_code(404);
    require_once 'views/errors/404.php';
  }
} else {
  // Controlador no encontrado
  http_response_code(404);
  require_once 'views/errors/404.php';
}