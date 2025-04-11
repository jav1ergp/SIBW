<?php
    function getPelicula($id) {
        esNumero($id);
        global $conn;
        
        $sql = "SELECT Titulo, Fecha, Genero, Director, Actores, descripcion FROM Pelicula WHERE id = $id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $pelicula = array(
            'titulo' => $row["Titulo"],
            'fecha' => $row["Fecha"],
            'genero' => $row["Genero"],
            'director' => $row["Director"],
            'actores' => $row["Actores"],
            'descripcion' => $row["descripcion"]
        );

        return $pelicula;
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
?>