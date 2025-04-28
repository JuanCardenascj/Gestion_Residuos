<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= APP_NAME ?></title>
  <link rel="stylesheet" href="/assets/css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container">
  <div class="header">
    <img src="/assets/img/Logo 1.1.png" alt="logo" class="logo">
    <span><?= APP_NAME ?> - 
      <?= USER_ROLES[$_SESSION['user']['role']] ?? 'Invitado' ?>
    </span>
  </div>
  
  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert success">
      <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
  <?php endif; ?>
  
  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert error">
      <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
  <?php endif; ?>