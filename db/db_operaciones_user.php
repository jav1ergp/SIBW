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
        $stmt->bind_param("ss", $comentario, $id,);
    
        return $stmt->execute();
    }
    
?>