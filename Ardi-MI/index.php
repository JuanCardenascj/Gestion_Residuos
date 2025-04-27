<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

// Redirigir según estado de autenticación
if (isLoggedIn()) {
    $user = $_SESSION['user'];
    if ($user['role'] === 'admin') {
        header('Location: pages/admin/dashboard.php');
    } else {
        header('Location: pages/dashboard.php');
    }
    exit();
} else {
    header('Location: pages/login.php');
    exit();
}
?>