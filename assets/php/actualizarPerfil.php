<?php
session_start();
include "../BD/db.php";

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../html/iniciarSesion.html");
    exit();
}

// Obtener los datos del formulario
$id = $_POST["id"];
$usuario = $_POST["usuario"];
$email = $_POST["email"];
$nueva_contrasena = $_POST["nueva_contrasena"];

// Preparar la consulta para actualizar los datos
if (!empty($nueva_contrasena)) {
    // Si se proporciona una nueva contraseña, encriptarla
    $nueva_contrasena = hash('sha512', $nueva_contrasena);
    $query = $conex->prepare("UPDATE usuarios SET usuario = ?, email = ?, contrasena = ? WHERE id = ?");
    $query->bind_param("sssi", $usuario, $email, $nueva_contrasena, $id);
} else {
    // Si no se proporciona una nueva contraseña, solo actualizar usuario y correo
    $query = $conex->prepare("UPDATE usuarios SET usuario = ?, email = ? WHERE id = ?");
    $query->bind_param("ssi", $usuario, $email, $id);
}

// Ejecutar la consulta
if ($query->execute()) {
    // Si la actualización es exitosa, actualizar la sesión y redirigir con parámetro
    $_SESSION['usuario'] = $usuario; // Actualizar el usuario en la sesión
    header("Location: editarPerfil.php?updated=true"); // Redirigir a la página de edición con el parámetro updated=true
    exit();
} else {
    // Si ocurre un error, redirigir con parámetro de error
    header("Location: editarPerfil.php?error=update_failed"); // Redirigir al formulario de edición con el parámetro error
    exit();
}


mysqli_close($conex);
?>
