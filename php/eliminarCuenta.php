<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Cuenta</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        /* Estilos generales */
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

        .modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(20px);
            text-align: center;
            padding: 2rem;
            z-index: 2;
        }

        .modal h2 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .modal p {
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        .boton {
            display: inline-block;
            text-decoration: none;
            margin: 0.5rem;
            padding: 0.7rem 1rem;
            border: none;
            background: linear-gradient(45deg, #c084fc, #f9a826);
            color: #fff;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .boton:hover {
            background: linear-gradient(45deg, #a56eff, #f7b84b);
            transform: scale(1.05);
        }

        .boton.cancelar {
            background: linear-gradient(45deg, #ff4d4d, #ff8c42);
            text-decoration: none;
        }

        .boton.cancelar:hover {
            background: linear-gradient(45deg, #ff6b6b, #ffb37a);
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1;
        }
    </style>
</head>

<body>

    <?php
    session_start();

    // Verificar si el usuario está logueado
    if (!isset($_SESSION['usuario'])) {
        header("Location: ../html/iniciarSesion.html"); // Si no está logueado, redirigir al login
        exit();
    }

    // Si la confirmación de eliminación fue recibida
    if (isset($_GET['confirmar']) && $_GET['confirmar'] == 'si') {
        include "../BD/db.php";

        $usuario = $_SESSION['usuario'];

        // Eliminar el usuario de la base de datos
        $query = "DELETE FROM usuarios WHERE usuario = '$usuario'";
        $eliminar = mysqli_query($conex, $query);

        if ($eliminar) {
            // Si la eliminación es exitosa, destruye la sesión y redirige al login
            session_unset();
            session_destroy();
            header("Location: ../html/iniciarSesion.html");
            exit();
        } else {
            echo '<script>alert("Hubo un problema al eliminar tu cuenta. Intenta de nuevo.");</script>';
        }

        mysqli_close($conex);
    }
    ?>

    <!-- Overlay para el modal -->
    <div class="overlay"></div>

    <!-- Modal de confirmación -->
    <div class="modal">
        <h2>¿Eliminar Cuenta?</h2>
        <p>Esta acción no se puede deshacer. ¿Estás seguro de que deseas eliminar tu cuenta?</p>
        <a href="eliminarCuenta.php?confirmar=si" class="boton">Sí, eliminar</a>
        <a href="bienvenida.php" class="boton cancelar">Cancelar</a>
    </div>

</body>

</html>
