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

    if(isset($_SESSION['error'])){
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
    } else{
        $error = null;
    }

    if(isset($_SESSION['usuario'])){
        $usuario = true;
        $rol = $_SESSION['usuario']['rol'];
    }else{
        $usuario = false;
        $rol = "anonimo";
    }
    
    $ImagenesIndex = getImagenesIndex();

    echo $twig->render('portada.html', [
        'imagenes' => $ImagenesIndex,
        'usuario' => $usuario,
        'error' => $error,
        'rol' => $rol
    ]);
?>
