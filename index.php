<?php
    require_once '/usr/local/lib/php/vendor/autoload.php';
    require_once 'db/db_connection.php';
    require_once 'db/db_operaciones.php';
    require_once 'db/db_operaciones_user.php';

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $error = null;
    
    if(isset($_POST['login'])) {
        $email = $_POST['correo'];
        $password = $_POST['password'];
        $usuarioValido = verificarUsuario($email, $password);
        
        if ($usuarioValido) {
            $_SESSION['usuario'] = $usuarioValido;
            $usuario = true;
        } else {
            $error = "Credenciales incorrectas.";
        }
    }

    if(isset($_POST['logout'])) {
        $_SESSION['usuario'] = [];
        session_destroy();

        header("Location: index.php"); // redirige después de logout
        exit;
    }

    if(isset($_SESSION['usuario']))
        $usuario = true;
    else
        $usuario = false;


    $ImagenesIndex = getImagenesIndex();

    echo $twig->render('portada.html', [
        'imagenes' => $ImagenesIndex,
        'usuario' => $usuario,
        'error' => $error
    ]);
?>
