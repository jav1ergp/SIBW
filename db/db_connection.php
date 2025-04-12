<?php
    require_once 'db_credencials.php';
        
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
   
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

?>