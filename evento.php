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

    if(isset($_SESSION['usuario'])){
        $usuario = true;
        $rol = $_SESSION['usuario']['rol'];
    } else{
        $usuario = false;
        $rol = "anonimo";
    }

    if(isset($_SESSION['error'])){
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
    } else{
        $error = null;
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $Pelicula = getPelicula($id);
        if ($id < 1 || $Pelicula === null) {
            $id = 1;
            $Pelicula = getPelicula($id);
        }
    } else {
        $id = 1;
        $Pelicula = getPelicula($id);
    }
    
    $Comentario = getComentarios($id);
    $Palabras = getPalabras();
    $Descripcion = $Pelicula['descripcion'];
    $Imagenes = getImagenes($id);

    $view = 'pelicula.html';
    if (isset ($_GET['imprimir'])){
        $view = 'pelicula_imprimir.html';
    } else {
        $Descripcion = nl2br($Descripcion); # Saltos de linea
    }

    $conn->close();

    echo $twig->render($view, [
        'pelicula' => $Pelicula,
        'descripcion' => $Descripcion,
        'imagenes' => $Imagenes,
        'id' => $id,
        'comentarios' => $Comentario,
        'palabras' => $Palabras,
        'usuario' => $usuario,
        'rol' => $rol,
        'error' => $error
    ]);
?>