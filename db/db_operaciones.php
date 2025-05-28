<?php
    // Datos 1 pelicula
    function getPelicula($id) {
        esNumero($id);
        global $conn;
        
        $stmt = $conn->prepare("SELECT Titulo, Fecha, Genero, Director, Actores, descripcion, id FROM Pelicula WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $fecha = date("Y-m-d", strtotime($row["Fecha"]));

        $pelicula = array(
            'titulo' => $row["Titulo"],
            'fecha' => $fecha,
            'genero' => $row["Genero"],
            'director' => $row["Director"],
            'actores' => $row["Actores"],
            'descripcion' => $row["descripcion"], 
            'id' => $row["id"]
        );
        
        return $pelicula;
    }

    // Datos todas las peliculas
    function getPeliculas() {
        global $conn;
        
        $sql = "SELECT Titulo, id FROM Pelicula";
        $result = $conn->query($sql);
        
        $peliculas = [];
        
        while ($row = $result->fetch_assoc()) {
            $peliculas[] = array(
                'titulo' => $row["Titulo"],
                'id' => $row["id"]
            );
        }
        
        return $peliculas;
    }

    // Datos todos los usuarios
    function getUsuarios() {
        global $conn;
        
        $sql = "SELECT Email, Rol FROM Usuarios";
        $result = $conn->query($sql);
        
        $usuarios = [];
        
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = array(
                'email' => $row["Email"],
                'rol' => $row["Rol"]
            );
        }
        
        return $usuarios;
    }

    // Datos 1 comentario
    function getComentarios($id) {
        esNumero($id);
        global $conn;
        
        $stmt = $conn->prepare("SELECT Autor, Fecha, Email, Comentario, id FROM Comentario WHERE idPelicula = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $comentarios = [];

        while ($row = $result->fetch_assoc()) {
            $comentarios[] = array(
                'autor' => $row["Autor"],
                'fecha' => $row["Fecha"],
                'email' => $row["Email"],
                'comentario' => $row["Comentario"],
                'id' => $row["id"]
            );
        }

        return $comentarios;
    }

    // Datos todas las palabras prohibidas
    function getPalabras(){
        global $conn;

        $sql = "SELECT Palabra FROM Palabras";
        $result = $conn->query($sql);

        $palabras = [];

        while($row = $result->fetch_assoc()) {
            $palabras[] = $row['Palabra'];
        }

        return $palabras;
    }

    // Datos 1 imagen
    function getImagenes($id) {
        esNumero($id);
        global $conn;

        $sql = "SELECT imagen, Nombre, id FROM Imagenes WHERE idPelicula = $id AND es_index = 0";
        $result = $conn->query($sql);
        $imagenes = [];
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imagenes[] = array(
                    'imagen' => base64_encode($row["imagen"]),
                    'nombre' => $row["Nombre"],
                    'id' => $row["id"]
                );
            }
        }
    
        return $imagenes;
    }

    // Datos de todas las imagenes de las portadas
    function getImagenesIndex(){
        global $conn;
        $sql = "SELECT imagen, Nombre, idPelicula FROM Imagenes WHERE es_index = 1";
        $result = $conn->query($sql);
        $imagenesIndex = [];
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imagenesIndex[] = array(
                    'imagen' => base64_encode($row["imagen"]),
                    'titulo' => $row["Nombre"],
                    'id' => $row["idPelicula"]
                );
            }
        }
    
        return $imagenesIndex;
    }

    // Comprueba si id es un numero
    function esNumero($id){
        if(!is_numeric($id)){
            die("Error en la URL");
        }
    }

    // Datos de los comentarios de todas las peliculas
    function getComentPeliculas(){
        global $conn;

        $sql = "SELECT Pelicula.Titulo, Comentario.id, Comentario.Comentario, Comentario.Autor, Comentario.Email, Comentario.Fecha
                FROM Pelicula JOIN Comentario 
                ON Pelicula.id = Comentario.idPelicula";
        $result = $conn->query($sql);

        $comentarios_pelicula = [];

        while ($row = $result->fetch_assoc()) {
            $titulo = $row['Titulo'];
        
            if (!isset($comentarios_pelicula[$titulo])) {
                $comentarios_pelicula[$titulo] = [];
            }
        
            $comentarios_pelicula[$titulo][] = array(
                'comentario' => $row['Comentario'],
                'autor' => $row['Autor'],
                'fecha' => $row['Fecha'],
                'id' => $row['id'],
            );
        }

        return $comentarios_pelicula;
    }

    // Datos 1 comentario
    function getComentario($id) {
        esNumero($id);
        global $conn;
        
        $sql = "SELECT Autor, Fecha, Email, Comentario FROM Comentario WHERE id = $id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $comentario = [
            'autor' => $row["Autor"],
            'fecha' => $row["Fecha"],
            'email' => $row["Email"],
            'comentario' => $row["Comentario"]
        ];

        return $comentario;
    }

    // Datos 1 usuario
    function getUsuario($email) {    
        global $conn;
    
        $stmt = $conn->prepare("SELECT Nombre, Email, Passwordd, Rol FROM Usuarios WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    
        if (!$row) return null;
    
        return [
            'nombre' => $row["Nombre"],
            'email' => $row["Email"],
            'password' => $row["Passwordd"],
            'rol' => $row["Rol"]
        ];
    }
    
    // Cambia el rol
    function modificarRol($email, $rol) {    
        global $conn;
    
        $stmt = $conn->prepare("UPDATE Usuarios SET Rol = ? WHERE Email = ?");
        $stmt->bind_param("ss", $rol, $email);
    
        return $stmt->execute();
    }

    // Cuenta los supers del sistema
    function contarSupers() {
        global $conn;
    
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM Usuarios WHERE Rol = 'superusuario'");
        $stmt->execute();
    
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    
        return $row['total'];
    }


    function buscarPeliculas($peli) {
        global $conn;

        $peli = "%" . $peli . "%";
        $stmt = $conn->prepare("SELECT Titulo, id FROM Pelicula WHERE Titulo LIKE ?");
        $stmt->bind_param("s", $peli);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $peliculas = [];
        while ($row = $result->fetch_assoc()) {
            $peliculas[] = array(
                'titulo' => $row["Titulo"],
                'id' => $row["id"]
            );
        }
        
        return $peliculas;
    }
?>