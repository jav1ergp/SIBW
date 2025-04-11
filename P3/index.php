<?php
    require_once '/usr/local/lib/php/vendor/autoload.php';
    require_once 'db/db_connection.php';
    require_once 'db/db_operaciones.php';

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $ImagenesIndex = getImagenesIndex();

    echo $twig->render('portada.html', [
        'imagenes' => $ImagenesIndex
    ]);
?>
