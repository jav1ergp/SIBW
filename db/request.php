<?php
    require_once 'db_connection.php';
    require_once 'db_operaciones_user.php';
    

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_POST['login'])) {
        $email = $_POST['correo'];
        $password = $_POST['password'];
        $usuarioValido = verificarUsuario($email, $password);
        
        if ($usuarioValido) {
            $_SESSION['usuario'] = $usuarioValido;
            $usuario = true;
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['error'] = "Credenciales incorrectas.";
        }
    }

    if(isset($_POST['logout'])) {
        $_SESSION['usuario'] = [];
        session_unset();
        session_destroy();

        header("Location: index.php");
        exit;
    }

    if(isset($_POST['registro'])) {
        $nombre = $_POST['nombre'];
        $email = $_POST['correo'];
        $password = $_POST['password'];
        $rol = $_POST['rol'];

        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        if(!esValido($email)){
            $_SESSION['error_registro'] = "Error al registrar el usuario.";
            header("Location: registro.php");
            exit;
        }

        $registro = registrarUsuario($nombre, $email, $password_hashed, $rol);

        if ($registro) {
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['error_registro'] = "Error al registrar el usuario.";
            header("Location: registro.php");
            exit;
        }
    }

    if(isset($_POST['modificar'])) {
        $nombre = $_POST['nombre'];
        $email = $_POST['correo'];
        $password = $_POST['password'];
        $rol = $_POST['rol'];

        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        $modificar = modificarUsuario($nombre, $email, $password_hashed, $rol);

        if ($modificar) {
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['error_modificar'] = "Error al modificar el usuario.";
            header("Location: perfil.php");
            exit;
        }
    }
?>