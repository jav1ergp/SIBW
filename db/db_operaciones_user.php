<?php
    function verificarUsuario($email, $password){
        global $conn;
        
        $email = $conn->real_escape_string($email);

        // Consultar la base de datos por el usuario
        $sql = "SELECT Nombre, Passwordd, Rol FROM Usuarios WHERE Email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Si el usuario existe, verificar la contraseña
            $row = $result->fetch_assoc();

            if ($password == $row['Passwordd']) {
                // La contraseña es correcta, retornar la información del usuario
                return [
                    'nombre' => $row['Nombre'],
                    'email' => $email,
                    'rol' => $row['Rol']
                ];
            }
        }

        // Si las credenciales no son correctas, retornar false
        return false;
    }
?>