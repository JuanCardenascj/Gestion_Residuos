<?php
require_once '../models/User.php';

class AuthController {
  private $userModel;

  public function __construct() {
    $this->userModel = new User();
  }

  public function login() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $email = $_POST['email'];
      $password = $_POST['password'];
      $whatsapp = isset($_POST['whatsapp_notification']);
      
      $user = $this->userModel->authenticate($email, $password);
      
      if ($user) {
        $_SESSION['user'] = $user;
        $_SESSION['user']['whatsapp'] = $whatsapp;
        
        header('Location: /dashboard');
        exit();
      } else {
        $_SESSION['error'] = 'Credenciales incorrectas';
        header('Location: /login');
        exit();
      }
    }
    
    require_once '../views/auth/login.php';
  }

  public function register() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        'phone' => $_POST['phone'],
        'role' => $_POST['role'],
        'whatsapp' => isset($_POST['whatsapp_notification'])
      ];
      
      if ($this->userModel->create($data)) {
        $_SESSION['success'] = 'Registro exitoso. Ahora puedes iniciar sesión';
        header('Location: /login');
        exit();
      } else {
        $_SESSION['error'] = 'Error al registrar usuario';
      }
    }
    
    require_once '../views/auth/register.php';
  }

  public function logout() {
    session_destroy();
    header('Location: /login');
    exit();
  }

  public function passwordRecovery() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $email = $_POST['email'];
      
      if ($this->userModel->sendRecoveryEmail($email)) {
        $_SESSION['success'] = 'Se ha enviado un enlace de recuperación a tu correo';
        header('Location: /login');
        exit();
      } else {
        $_SESSION['error'] = 'Correo no encontrado';
      }
    }
    
    require_once '../views/auth/recovery.php';
  }
}
?>