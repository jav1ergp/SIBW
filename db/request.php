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

        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        if(!esValido($email)){
            $_SESSION['error_registro'] = "Error al registrar el usuario.";
            header("Location: registro.php");
            exit;
        }

        $registro = registrarUsuario($nombre, $email, $password_hashed);

        if ($registro) {
            $usuarioValido = verificarUsuario($email, $password);
            $_SESSION['usuario'] = $usuarioValido;
            $usuario = true;
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['error_registro'] = "Error al registrar el usuario.";
            header("Location: registro.php");
            exit;
        }
    }

    if(isset($_POST['modificar']) and isset($_SESSION['usuario'])) {
        $nombre = $_POST['nombre'];
        $email = $_POST['correo'];
        $email_viejo = $_SESSION['usuario']['email'];
        $password = $_POST['password'];
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        if ($_SESSION['usuario']['rol'] == "superusuario") {
            $rol = $_POST['rol'];
        } else {
            $rol = $_SESSION['usuario']['rol'];
        }

        $modificar = modificarUsuario($nombre, $email, $email_viejo, $password_hashed, $rol);

        if ($modificar) {
            $usuarioValido = verificarUsuario($email, $password);
            $_SESSION['usuario'] = $usuarioValido;
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['error_modificar'] = "Error al modificar el usuario.";
            header("Location: perfil.php");
            exit;
        }
    }

    if(isset($_POST['Modificar_coment'])) {
        $comentario = $_POST['comentario'];
        $id = $_POST['id'];

        $modificar = modificarComentario($comentario, $id);

        if ($modificar) {
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['error_modificar_coment'] = "Error al modificar el comentario.";
            $_SESSION['id'] = $id;
            header("Location: editar_comentario.php");
            exit;
        }
    }

    if(isset($_POST['Añadir_coment'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $comentario = $_POST['comentario'];
        
        $añadir = añadirComentario($nombre, $email, $comentario, $id);

        if ($añadir) {
            header("Location: evento.php?id=$id");
            exit;
        } else {
            header("Location: editar_comentario.php");
            exit;
        }
    }

    if(isset($_POST['Borrar_coment'])) {
        $id = $_POST['id'];

        borrarComentario($id);

        header("Location: index.php");
        exit;        
    }

    if(isset($_POST['Borrar_pelicula'])) {
        $id = $_POST['id'];

        borrarPelicula($id);

        header("Location: index.php");
        exit;        
    }

    if(isset($_POST['añadirPelicula'])) {
        $titulo = $_POST['titulo'];
        $date = $_POST['fecha'];
        $genero = $_POST['genero'];
        $director = $_POST['director'];
        $actores = $_POST['actores'];
        $descripcion = $_POST['descripcion'];
        $imagen_tmp = $_FILES['imagen']['tmp_name'];
        $imagen_data = file_get_contents($imagen_tmp);

        $pelicula = añadirPelicula($titulo, $date, $genero, $director, $actores, $descripcion);
        añadirImagenPortada($titulo, $imagen_data, $pelicula);

        if ($pelicula) {
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['error_pelicula'] = "Error al registrar una pelicula.";
            header("Location: añadir_pelicula.php");
            exit;
        }
    }

    if(isset($_POST['editarPelicula'])) {
        $id = $_POST['id'];
        $titulo = $_POST['titulo'];
        $date = $_POST['fecha'];
        $genero = $_POST['genero'];
        $director = $_POST['director'];
        $actores = $_POST['actores'];
        $descripcion = $_POST['descripcion'];
        if(isset( $_POST['imagen_titulo'])){
            $imagen_titulo = $_POST['imagen_titulo'];
        }
        
        $borrar_imagen = $_POST['imagen_seleccionada'];
        
        
        borrar_imagen($borrar_imagen);
        
        
        $pelicula = editarPelicula($id, $titulo, $date, $genero, $director, $actores, $descripcion);
        
        if ($pelicula && $_FILES['imagen_index']['error'] === UPLOAD_ERR_OK) {
            $imagen_tmp = $_FILES['imagen_index']['tmp_name'];
            $imagen_data = file_get_contents($imagen_tmp);
        
            editar_imagen($id, $imagen_data, $titulo);  // Actualizar imagen index
        }
        

        if ($pelicula && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagen_tmp = $_FILES['imagen']['tmp_name'];
            $imagen_data = file_get_contents($imagen_tmp);
    
            añadirImagen($imagen_titulo, $imagen_data, $id);
        }
        
        if ($pelicula) {
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['error_pelicula'] = "Error al modificar una pelicula.";
            header("Location: editar_pelicula2.php");
            exit;
        }
    }

    if(isset($_POST['modificarRol'])) {
        $email = $_POST['email'];
        $rol = $_POST['rol'];
        $previoRol = $_POST['previoRol'];
        $nsupers = contarSupers();
        
        if($previoRol == "superusuario" && $previoRol != $rol && $nsupers == 1) {
            header("Location: panel.php");
            exit;
        } else {
            modificarRol($email, $rol);
            header("Location: index.php");
            exit;
        }
    }
?>