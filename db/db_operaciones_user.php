<?php
    function verificarUsuario($email, $password){
        global $conn;
        
        $email = $conn->real_escape_string($email);

        $sql = "SELECT Nombre, Passwordd, Rol FROM Usuarios WHERE Email = '$email'";
        $result = $conn->query($sql);

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

    function registrarUsuario($nombre, $email, $password, $rol) {
        global $conn;

        if (!esValido($email)){
            return False;
        }
        
        $stmt = $conn->prepare("INSERT INTO Usuarios (Email, Nombre, Passwordd, Rol) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $email, $nombre, $password, $rol);

        return $stmt->execute();

    }

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

    function borrarComentario($id) {
        global $conn;

        $stmt = $conn->prepare("DELETE FROM Comentario WHERE id=?");
        $stmt->bind_param("s", $id);
    
        return $stmt->execute();
    }
    
    function borrarPelicula($id) {
        global $conn;

        $stmt = $conn->prepare("DELETE FROM Pelicula WHERE id=?");
        $stmt->bind_param("s", $id);
    
        return $stmt->execute();
    }

    function añadirPelicula($titulo, $date, $genero, $director, $actores, $descripcion) {
        global $conn;

        $stmt = $conn->prepare("INSERT INTO Pelicula (Titulo, Fecha, Genero, Director, Actores, descripcion) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss", $titulo, $date, $genero, $director, $actores, $descripcion);

        if ($stmt->execute()) {
            return $conn->insert_id;
        } else {
            return false;
        }
    }

    function editarPelicula($id, $titulo, $date, $genero, $director, $actores, $descripcion) {
        global $conn;

        $stmt = $conn->prepare("UPDATE Pelicula SET Titulo = ?, Fecha = ?, Genero = ?, Director = ?, Actores = ?, descripcion = ? WHERE id = ?");
    
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param("ssssssi", $titulo, $date, $genero, $director, $actores, $descripcion, $id);

        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    function añadirImagenPortada($titulo, $imagen, $idPelicula) {
        global $conn;
    
        $es_index = true;
    
        $stmt = $conn->prepare("INSERT INTO Imagenes (Nombre, imagen, idPelicula, es_index) VALUES (?, ?, ?, ?)");
        $stmt->send_long_data(1, $imagen);
        $stmt->bind_param("ssii", $titulo, $imagen, $idPelicula, $es_index);
    
        return $stmt->execute();
    }
    function añadirImagen($titulo, $imagen, $idPelicula) {
        global $conn;
    
        $es_index = false;
    
        $stmt = $conn->prepare("INSERT INTO Imagenes (Nombre, imagen, idPelicula, es_index) VALUES (?, ?, ?, ?)");
        $stmt->send_long_data(1, $imagen);
        $stmt->bind_param("sssi", $titulo, $imagen, $idPelicula, $es_index);
    
        return $stmt->execute();
    }
?>