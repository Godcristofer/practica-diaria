<?php
session_start();
include "../BD/db.php";

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../html/iniciarSesion.html");
    exit();
}

$usuario = $_SESSION['usuario']; // Obtener el usuario logueado
$query = $conex->prepare("SELECT * FROM usuarios WHERE usuario = ?");
$query->bind_param("s", $usuario);
$query->execute();
$result = $query->get_result();
$usuario_info = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Editar Perfil</title>
        <style>
        /* Estilo para el fondo y la página */
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

        /* Estilo del contenedor principal */
        .contenedor {
            width: 400px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(20px);
            z-index: 1;
            padding: 2rem;
        }

        h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
            color: #fff;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        label {
            font-size: 0.9rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 93%;
            padding: 0.7rem;
            border: none;
            background: rgba(255, 255, 255, 0.8);
            color: #333;
            border-radius: 8px;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.8);
        }

        input[type="submit"] {
            width: 100%;
            padding: 0.7rem;
            border: none;
            background: linear-gradient(45deg, #c084fc, #f9a826);
            color: #fff;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        input[type="submit"]:hover {
            background: linear-gradient(45deg, #a56eff, #f7b84b);
            transform: scale(1.05);
        }

        .boton-regresar {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            text-decoration: none;
            color: #fff;
            background: linear-gradient(45deg, #ff8c42, #ff4d4d);
            padding: 0.7rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .boton-regresar:hover {
            background: linear-gradient(45deg, #ffb37a, #ff6b6b);
            transform: scale(1.05);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 2;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
        }

        .modal-content h2 {
            text-align: center;
            font-size: 1.6rem;
            margin-bottom: 1rem;
        }

        .modal-content p {
            margin-bottom: 1.5rem;
        }

        .modal-content .boton {
            background: linear-gradient(45deg, #ff8c42, #ff4d4d);
            padding: 0.7rem 1.2rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .modal-content .boton:hover {
            background: linear-gradient(45deg, #ffb37a, #ff6b6b);
            transform: scale(1.05);
        }

        .boton-cerrar {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.2rem;
            color: #fff;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .contenedor {
                width: 90%;
            }

            h1 {
                font-size: 1.4rem;
            }

            .boton-regresar {
                font-size: 0.8rem;
                padding: 0.5rem;
            }
        }
        </style>
    </head>

<body>
    <div class="contenedor">
        <h1>Editar Perfil</h1>
        <form action="actualizarPerfil.php" method="POST">
            <input type="hidden" name="id" value="<?= $usuario_info['id'] ?>">

            <label for="usuario">Nombre de usuario:</label>
            <input type="text" id="usuario" name="usuario" value="<?= $usuario_info['usuario'] ?>" required>

            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" value="<?= $usuario_info['email'] ?>" required>

            <label for="nueva_contrasena">Nueva contraseña:</label>
            <input type="password" id="nueva_contrasena" name="nueva_contrasena" placeholder="Dejar en blanco si no cambia">

            <input type="submit" value="Actualizar Perfil">
        </form>
        <a href="bienvenida.php" class="boton-regresar">Volver a la página de bienvenida</a>
    </div>

    <!-- Modal -->
    <div class="modal" id="modal">
        <div class="modal-content">
            <span class="boton-cerrar" onclick="cerrarModal()">&times;</span>
            <h2>¡Perfil actualizado!</h2>
            <p>Tu perfil se ha actualizado correctamente.</p>
            <button class="boton" onclick="cerrarModal()">Cerrar</button>
        </div>
    </div>

    <script>
    // Función para abrir el modal
    function abrirModal() {
        document.getElementById('modal').style.display = 'flex';
    }

    // Función para cerrar el modal
    function cerrarModal() {
        document.getElementById('modal').style.display = 'none';
    }

    // Abrir el modal solo si la URL contiene el parámetro "updated=true"
    window.onload = function() {
        const params = new URLSearchParams(window.location.search);
        if (params.get('updated') === 'true') {
            abrirModal();
        }
    };
    </script>

</body>

</html>
