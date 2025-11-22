<?php
// login.php - mejorar seguridad y manejo de errores
require_once __DIR__ . "/../BD/db.php"; // contiene la conexión mysqli en $conex y puede iniciar sesión

// Asegurarse que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/iniciarSesion.html');
    exit();
}

$usuario = trim($_POST['usuario'] ?? '');
$password = $_POST['nueva_contrasena'] ?? '';

// validación básica en servidor
if ($usuario === '' || $password === '') {
    header('Location: ../html/iniciarSesion.html?error=1');
    exit();
}

// Usar prepared statements con mysqli
$stmt = $conex->prepare('SELECT id, usuario, contrasena FROM usuarios WHERE usuario = ? LIMIT 1');
if (!$stmt) {
    error_log('Prepare failed: ' . $conex->error);
    header('Location: ../html/iniciarSesion.html?error=1');
    exit();
}

$stmt->bind_param('s', $usuario);
if (!$stmt->execute()) {
    error_log('Execute failed: ' . $stmt->error);
    $stmt->close();
    header('Location: ../html/iniciarSesion.html?error=1');
    exit();
}

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    // No revelar si el usuario no existe
    $stmt->close();
    header('Location: ../html/iniciarSesion.html?error=1');
    exit();
}

$row = $result->fetch_assoc();
$stmt->close();

$storedHash = $row['contrasena'];
$userId = $row['id'];

$authenticated = false;

// Si el hash almacenado parece generado por password_hash (por ejemplo empieza con $2y$ o $argon2), usar password_verify
if (strpos($storedHash, '$2y$') === 0 || strpos($storedHash, '$2a$') === 0 || stripos($storedHash, 'argon2') !== false) {
    if (password_verify($password, $storedHash)) {
        $authenticated = true;
    }
} else {
    // Compatibilidad: si la base de datos guarda SHA-512 hex, compararlo
    if (hash('sha512', $password) === $storedHash) {
        $authenticated = true;
    }
    // También intentar password_verify por si fue migrado parcialmente
    if (!$authenticated && password_verify($password, $storedHash)) {
        $authenticated = true;
    }
}

if ($authenticated) {
    // Asegurarse de tener sesión activa
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    // Mejorar seguridad de la sesión
    session_regenerate_id(true);
    $_SESSION['usuario'] = $usuario;
    $_SESSION['user_id'] = $userId;
    header('Location: ../php/bienvenida.php');
    exit();
} else {
    header('Location: ../html/iniciarSesion.html?error=1');
    exit();
}

?>