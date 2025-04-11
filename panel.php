<?php
    require_once '/usr/local/lib/php/vendor/autoload.php';
    require_once 'db/db_connection.php';
    require_once 'db/db_operaciones.php';

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_SESSION['usuario']))
        $usuario = true;
    else
        $usuario = false;

    $rol = $_SESSION['usuario']['rol'];

    echo $twig->render('panel.html', [
        'usuario' => $usuario,
    ]);
?>
