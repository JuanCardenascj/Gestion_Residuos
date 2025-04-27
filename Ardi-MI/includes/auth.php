<?php
function isLoggedIn() {
    return isset($_SESSION['user']);
}

function login($email, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        return true;
    }
    
    return false;
}

function logout() {
    session_unset();
    session_destroy();
}

function registerUser($data) {
    global $pdo;
    
    // Validar datos
    if ($data['password'] !== $data['confirm_password']) {
        return ['success' => false, 'message' => 'Las contraseñas no coinciden'];
    }
    
    // Verificar si el email ya existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Este correo ya está registrado'];
    }
    
    // Crear hash de contraseña
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    
    // Insertar nuevo usuario
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, role, whatsapp_notifications) 
                          VALUES (?, ?, ?, ?, ?, ?)");
    $success = $stmt->execute([
        $data['name'],
        $data['email'],
        $hashedPassword,
        $data['phone'],
        $data['role'],
        $data['whatsapp'] ? 1 : 0
    ]);
    
    if ($success) {
        return ['success' => true, 'message' => 'Registro exitoso'];
    } else {
        return ['success' => false, 'message' => 'Error al registrar el usuario'];
    }
}
?>