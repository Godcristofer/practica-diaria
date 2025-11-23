<?php

session_start();

include "../BD/db.php";

$usuario = $_POST['usuario'];
$nueva_contrasena = hash('sha512', $_POST['nueva_contrasena']); // Asegúrate de encriptar la contraseña antes de verificarla

// Consulta para verificar las credenciales
$validar_login = mysqli_query($conex, "SELECT * FROM usuarios WHERE usuario = '$usuario' AND contrasena = '$nueva_contrasena'");

if (mysqli_num_rows($validar_login) > 0) {
    $_SESSION['usuario'] = $usuario; // Guarda el usuario en la sesión
    header("Location: bienvenida.php"); // Redirige a la pagina exclusiva para usuarios logeados
    exit();
} else {
    echo '
        <script>
            alert("Usuario o contraseña incorrectos. Por favor, verifica tus datos.");
            window.location.href = "../html/iniciarSesion.html";
        </script>
    ';
    exit();
}

?>