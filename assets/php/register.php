<?php

include "../BD/db.php";

    $usuario = $_POST["usuario"];
    $email = $_POST["email"];
    $nueva_contrasena = $_POST["nueva_contrasena"];
    $repetir_contrasena = $_POST["repetir_contrasena"];

    //validacion inicial
    if ($_POST['nueva_contrasena'] !== $_POST['repetir_contrasena']) {
        echo '
            <script>
                alert("Las contraseñas no coinciden. Por favor, inténtalo de nuevo.");
                window.location.href = "../html/iniciarSesion.html";
            </script>
        ';
        exit();
    }

    //Encriptar contraseña de usuarios
    $nueva_contrasena = hash('sha512', $nueva_contrasena);

    $query = "INSERT INTO usuarios(usuario, email, contrasena) VALUES ('$usuario', '$email', '$nueva_contrasena')";

    //Verificar que el correo no se repita en la base de datos
    $verificar_correo = mysqli_query($conex, "SELECT * FROM usuarios WHERE email = '$email'");

    if(mysqli_num_rows($verificar_correo) > 0){
    echo '
            <script>
                alert("Este correo ya esta registrado, intenta con otro diferente");
                window.location.href = "../html/iniciarSesion.html";
            </script>
        ';
        exit();
    }

    //Verificar que el nombre de usuario no se repita en la base de datos
    $verificar_usuario = mysqli_query($conex, "SELECT * FROM usuarios WHERE usuario= '$usuario'");

    if(mysqli_num_rows($verificar_usuario) > 0){
    echo '
            <script>
                alert("Este nombre de usuario ya esta registrado, intenta con otro diferente");
                window.location.href = "../html/iniciarSesion.html";
            </script>
        ';
        exit();
    }

    $ejecutar = mysqli_query($conex, $query);

    if($ejecutar){
        echo '
            <script>
                alert("Usuario creado con exito");
                window.location.href = "../html/iniciarSesion.html";
            </script>
        ';
    }else{
        echo '
            <script>
                alert("Intentalo de nuevo, usuario no creado");
                window.location.href = "../html/iniciarSesion.html";
            </script>
        ';
    }

mysqli_close($conex);
?>



