<?php

require_once "../utils/conexion.php";
if ($_SERVER["REQUEST_METHOD"] === 'GET') {
    
}elseif ($_SERVER["REQUEST_METHOD"] === 'POST') {

    $idUsuario = $_POST['idUsuario'] ;
    $idCanal = $_POST['idCanal'] ;
    $contenido = $_POST['contenido'] ;

    if ($idUsuario !== null && $idCanal !== null && $contenido !== null) {
        
        $result = $conexion->query("INSERT INTO mensajes (id_usuario, id_canal, contenido) VALUES ('$idUsuario', '$idCanal', '$contenido')");

        if ($result) {
            echo '{"status": "succes"}';
        } else {
            
            http_response_code(500);
            echo '{"error": "Error en la base de datos"}';
        }
    } else {
        http_response_code(400);
        echo '{"error": "faltan parametros"}';
    }
} else {
    http_response_code(405);
    echo '{"error": "Metodo no permitido "}';
}

?>

