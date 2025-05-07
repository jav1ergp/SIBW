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

    if (isset($_SESSION['usuario']) && ($_SESSION['usuario']['rol'] == "gestor" || $_SESSION['usuario']['rol'] == "superusuario")) {
        $usuario = true;
        $rol = $_SESSION['usuario']['rol'];
    } else {
        header("Location: index.php");
        exit;
    }

    if (isset ($_POST['id'])){
        $id = $_POST['id'];
    }

    $pelicula = getPelicula($id);

    echo $twig->render("editar_pelicula.html", [
        'usuario' => $usuario,
        'rol' => $rol,
        'pelicula' => $pelicula,
        'id' => $id
    ]);
?>
