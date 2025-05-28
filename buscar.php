<?php  
require_once 'db/db_connection.php';  
require_once 'db/db_operaciones.php';  
  
if (isset($_GET['peli']) && !empty($_GET['peli'])) {  
    $termino = $_GET['peli'];  
    $resultados = buscarPeliculas($termino);  
      
    header('Content-Type: application/json');  
    echo json_encode($resultados);  
} else {  
    echo json_encode([]);  
}  
?>