<?php
    function getPelicula($id) {
        esNumero($id);
        global $conn;
        
        $sql = "SELECT Titulo, Fecha, Genero, Director, Actores, descripcion FROM Pelicula WHERE id = $id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        if (!$row) {
            return null;
        }
        
        $fecha = date("Y-m-d", strtotime($row["Fecha"]));

        $pelicula = array(
            'titulo' => $row["Titulo"],
            'fecha' => $fecha,
            'genero' => $row["Genero"],
            'director' => $row["Director"],
            'actores' => $row["Actores"],
            'descripcion' => $row["descripcion"]
        );
        
        return $pelicula;
    }

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

    function getComentarios($id) {
        esNumero($id);
        global $conn;
        
        $sql = "SELECT Autor, Fecha, Email, Comentario FROM Comentario WHERE idPelicula = $id";
        $result = $conn->query($sql);
        $comentarios = array();

        while ($row = $result->fetch_assoc()) {
            $comentarios[] = [
                'autor' => $row["Autor"],
                'fecha' => $row["Fecha"],
                'email' => $row["Email"],
                'comentario' => $row["Comentario"]
            ];
        }

        return $comentarios;
    }

    function getPalabras(){
        global $conn;

        $sql = "SELECT Palabra FROM Palabras";
        $result = $conn->query($sql);

        while($row = $result->fetch_assoc()) {
            $palabras[] = $row['Palabra'];
        }

        return $palabras;
    }

    function getImagenes($id) {
        esNumero($id);
        global $conn;

        $sql = "SELECT imagen, Nombre FROM Imagenes WHERE idPelicula = $id AND es_index = 0";
        $result = $conn->query($sql);
        $imagenes = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imagenes[] = [
                    'imagen' => base64_encode($row["imagen"]),
                    'nombre' => $row["Nombre"]
                ];
            }
        }
    
        return $imagenes;
    }

    function getImagenesIndex(){
        global $conn;
        $sql = "SELECT imagen, Nombre, idPelicula FROM Imagenes WHERE es_index = 1";
        $result = $conn->query($sql);
        $imagenesIndex = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imagenesIndex[] = [
                    'imagen' => base64_encode($row["imagen"]),
                    'titulo' => $row["Nombre"],
                    'id' => $row["idPelicula"]
                ];
            }
        }
    
        return $imagenesIndex;
    }

    function esNumero($id){
        if(!is_numeric($id)){
            die("Error en la URL");
        }
    }

    function getComentPeliculas(){
        global $conn;

        $sql = "SELECT Pelicula.Titulo, Comentario.id, Comentario.Comentario, Comentario.Autor, Comentario.Email, Comentario.Fecha, Comentario.Editado
                FROM Pelicula JOIN Comentario 
                ON Pelicula.id = Comentario.idPelicula";
        $result = $conn->query($sql);

        $comentarios_por_pelicula = [];

        while ($row = $result->fetch_assoc()) {
            $titulo = $row['Titulo'];
        
            if (!isset($comentarios_por_pelicula[$titulo])) {
                $comentarios_por_pelicula[$titulo] = [];
            }
        
            $comentarios_por_pelicula[$titulo][] = [
                'comentario' => $row['Comentario'],
                'autor' => $row['Autor'],
                'fecha' => $row['Fecha'],
                'id' => $row['id'],
                'editado' => $row['Editado'] ?? false
            ];
        }

        return $comentarios_por_pelicula;
    }

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
    

    function modificarRol($email, $rol) {    
        global $conn;
    
        $stmt = $conn->prepare("UPDATE Usuarios SET Rol = ? WHERE Email = ?");
        $stmt->bind_param("ss", $rol, $email);
    
        return $stmt->execute();
    }

    function contarSupers() {
        global $conn;
    
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM Usuarios WHERE Rol = 'superusuario'");
        $stmt->execute();
    
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    
        return $row['total'];
    }
    

?>