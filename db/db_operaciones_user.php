<?php
    // Login check
    function verificarUsuario($email, $password){
        global $conn;
        
        $stmt = $conn->prepare("SELECT Nombre, Passwordd, Rol FROM Usuarios WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['Passwordd'])) {
                return [
                    'nombre' => $row['Nombre'],
                    'email' => $email,
                    'rol' => $row['Rol']
                ];
            }
        }

        return false;
    }

    // Registrar 1 usuario en la base de datos
    function registrarUsuario($nombre, $email, $password) {
        global $conn;

        if (!esValido($email)){
            return False;
        }
        $rol = "registrado";

        $stmt = $conn->prepare("INSERT INTO Usuarios (Email, Nombre, Passwordd, Rol) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $email, $nombre, $password, $rol);

        return $stmt->execute();

    }

    // Comprueba si ya existe el email(No se puede repetir email)
    function esValido($email) {
        global $conn;
        $stmt = $conn->prepare("SELECT Email FROM Usuarios WHERE Email = (?)");
        $stmt->bind_param("s", $email);

        $stmt->execute();

        $stmt->store_result();

        if($stmt -> num_rows > 0) {
            return false;
        }
        return true;
    }

    // Cambia los datos de 1 usuario
    function modificarUsuario($nombre, $email, $email_viejo, $password, $rol) {
        global $conn;
    
        if($email != $email_viejo) {
            if(!esValido($email)){
                return false;
            }
        }

        $stmt = $conn->prepare("UPDATE Usuarios SET Nombre=?, Email=?, Passwordd=?, Rol=? WHERE Email=?");
        $stmt->bind_param("sssss", $nombre, $email, $password, $rol, $email_viejo);
    
        return $stmt->execute();
    }

    // Añade 1 comentario en la base de datos
    function añadirComentario($nombre, $email, $comentario, $idPelicula) {
        global $conn;

        $date = date("Y-m-d H:i:s");
        
        $stmt = $conn->prepare("INSERT INTO Comentario (Autor, Fecha, Email, Comentario, idPelicula) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $nombre, $date, $email, $comentario, $idPelicula);

        return $stmt->execute();
    }

    // Modifica 1 comentario
    function modificarComentario($comentario, $id){
        global $conn;

        if(empty($comentario) ) {
            return false;
        }

        $comentario = $comentario . "\n*Mensaje editado por un Moderador*";

        $stmt = $conn->prepare("UPDATE Comentario SET Comentario=? WHERE id=?");
        $stmt->bind_param("ss", $comentario, $id);
    
        return $stmt->execute();
    }

    // Borra 1 comentario
    function borrarComentario($id) {
        global $conn;

        $stmt = $conn->prepare("DELETE FROM Comentario WHERE id=?");
        $stmt->bind_param("s", $id);
    
        return $stmt->execute();
    }
    
    // Borra 1 pelicula
    function borrarPelicula($id) {
        global $conn;

        $stmt = $conn->prepare("DELETE FROM Pelicula WHERE id=?");
        $stmt->bind_param("s", $id);
    
        return $stmt->execute();
    }

    // Añade 1 pelicula
    function añadirPelicula($titulo, $date, $genero, $director, $actores, $descripcion) {
        global $conn;

        $stmt = $conn->prepare("INSERT INTO Pelicula (Titulo, Fecha, Genero, Director, Actores, descripcion) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss", $titulo, $date, $genero, $director, $actores, $descripcion);

        if ($stmt->execute()) {
            return $conn->insert_id; // id para luego meter imagenes asignada a esa peli
        } else {
            return false;
        }
    }

    // Modifica 1 pelicula y la imagen de la portada de esa pelicula(Titulo pelicula y Nombre Imagen son iguales)
    function editarPelicula($id, $titulo, $date, $genero, $director, $actores, $descripcion) {
        global $conn;

        $stmt = $conn->prepare("UPDATE Pelicula SET Titulo = ?, Fecha = ?, Genero = ?, Director = ?, Actores = ?, descripcion = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $titulo, $date, $genero, $director, $actores, $descripcion, $id);

        if (!$stmt) {
            return false;
        }

        $result = $stmt->execute();
        $stmt->close();
        
        $stmt2 = $conn->prepare("UPDATE Imagenes SET Nombre = ? WHERE idPelicula = ? AND es_index = 1");
        $stmt2->bind_param("si", $titulo, $id);

        if (!$stmt2) {
            return false;
        }

        $result2 = $stmt2->execute();
        $stmt2->close();

        return $result && $result2;
    }

    // Se añade 1 imagen a la portada(Cuando se crea una pelicula)
    function añadirImagenPortada($titulo, $imagen, $idPelicula) {
        global $conn;
    
        $es_index = true;
    
        $stmt = $conn->prepare("INSERT INTO Imagenes (Nombre, imagen, idPelicula, es_index) VALUES (?, ?, ?, ?)");
        $stmt->send_long_data(1, $imagen);
        $stmt->bind_param("ssii", $titulo, $imagen, $idPelicula, $es_index);
    
        return $stmt->execute();
    }

    // Se añade 1 imagen DENTRO de una pelicula
    function añadirImagen($titulo, $imagen, $idPelicula) {
        global $conn;
    
        $es_index = false;
    
        $stmt = $conn->prepare("INSERT INTO Imagenes (Nombre, imagen, idPelicula, es_index) VALUES (?, ?, ?, ?)");
        $stmt->send_long_data(1, $imagen);
        $stmt->bind_param("sssi", $titulo, $imagen, $idPelicula, $es_index);
    
        return $stmt->execute();
    }

    // Se borra 1 imagen de DENTRO de una pelicula
    function borrar_imagen($id) {
        global $conn;
    
        $stmt = $conn->prepare("DELETE FROM Imagenes WHERE id = ?");
        $stmt->bind_param("i", $id);
    
        return $stmt->execute();
    }
    
    // Cuando cambia la imagen de la PORTADA de una pelicula
    function editar_imagen($id, $imagen, $titulo) {
        global $conn;
    
        $stmt = $conn->prepare("UPDATE Imagenes SET Nombre = ?, imagen = ? WHERE idPelicula = ? AND es_index = 1");
        $stmt->send_long_data(1, $imagen);
        $stmt->bind_param("ssi", $titulo, $imagen, $id);
    
        return $stmt->execute();
    }
?>