<?php
    require_once '/usr/local/lib/php/vendor/autoload.php';
    require_once 'db/db_connection.php';
    require_once 'db/db_operaciones.php';
    require_once 'db/db_operaciones_user.php';
    require_once 'db/request.php';

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_SESSION['usuario'])){
        $usuario = true;
        $nombre = $_SESSION['usuario']['nombre'];
        $email = $_SESSION['usuario']['email'];
        $rol = $_SESSION['usuario']['rol'];
    } else {
        header("Location: index.php");
        exit;
    }
    
    if(isset($_SESSION['error_modificar'])){
        $error2 = $_SESSION['error_modificar'];
        unset($_SESSION['error_modificar']);
    } else{
        $error2 = null;
    }

    echo $twig->render("perfil.html", [
        'usuario' => $usuario,
        'nombre' => $nombre,
        'email' => $email,
        'rol' => $rol,
        'error2' => $error2
    ]);
?>
