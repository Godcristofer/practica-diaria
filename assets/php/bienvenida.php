<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../html/iniciarSesion.html"); // Si no está logueado, redirigir al login
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<title>Bienvenida</title>
<style>
/* Fondo con imagen y efecto blur */
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: url('../assets/imagenes/beautiful-outdoor-view-ocean-beach.jpg') no-repeat center center/cover;
    position: relative;
    overflow: hidden;
}

body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    backdrop-filter: blur(10px);
    background: rgba(0, 0, 0, 0.5);
    z-index: 0;
}

.container {
    width: 400px;
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
    backdrop-filter: blur(20px);
    z-index: 1;
    padding: 2rem;
    text-align: center;
}

h1 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: #fff;
}

a {
    display: block;
    margin: 1rem 0;
    text-decoration: none;
    color: #fff;
    background: linear-gradient(45deg, #c084fc, #f9a826);
    padding: 0.7rem 1rem;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

a:hover {
    background: linear-gradient(45deg, #a56eff, #f7b84b);
    transform: scale(1.05);
}

@media (max-width: 768px) {
    .container {
        width: 90%;
    }

    h1 {
        font-size: 1.4rem;
    }

    a {
        font-size: 0.9rem;
        padding: 0.5rem;
    }
}
</style>
</head>

<body>
<div class="container">
<h1>¡Bienvenido, <?= $_SESSION['usuario']; ?>!</h1>
<a href="editarPerfil.php">Editar mi perfil</a>
<a href="logout.php">Cerrar sesión</a>
<a href="eliminarCuenta.php">Eliminar mi cuenta</a>
</div>
</body>

</html>
