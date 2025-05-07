<?php
    require_once '/usr/local/lib/php/vendor/autoload.php';
    require_once 'db/db_connection.php';
    require_once 'db/db_operaciones.php';
    require_once 'db/request.php';

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    
    if (isset($_SESSION['usuario']) && ($_SESSION['usuario']['rol'] == "moderador" || $_SESSION['usuario']['rol'] == "superusuario")) {
        $usuario = true;
        $rol = $_SESSION['usuario']['rol'];
    } else {
        header("Location: index.php");
        exit;
    }

    if (isset ($_POST['id'])){
        $id = $_POST['id'];
    } else {
        header("Location: index.php");
        exit;
    }

    $comentario = getComentario($id);

    echo $twig->render("borrar_comentario.html", [
        'usuario' => $usuario,
        'rol' => $rol,
        'comentario' => $comentario,
        'id' => $id
    ]);
?>