<?php
require_once "../utils/conexion.php";
if($_SERVER["REQUEST_METHOD"]==='GET'){

}elseif($_SERVER['REQUEST_METHOD']=== 'POST'){
    usuarioMensaje($idUsuario,$idCanal,$idMensaje);
}
function usuarioMensaje($idMensaje,$idUsuario,$idCanal){
global $conexion;
 
if (!empty($_POST['idMensaje']) && !empty($_POST['idUsuario']) && !empty($_POST['idCanal'])) {
    
    $idCanal = $_POST['idUsuario'];
    $idMensaje = $_POST['idCanal'];
$idContenido = $_POST[''];
    $result = $conexion->query("INSERT INTO mensajes (id_mensaje, id_usuario, id_canal,id_contenido) VALUES ('$idMensaje','$idCanal','$idUsuario')");

    if ($result) {    
        echo '{"status":"success"}';
    } else {
        header("HTTP/1.1 500 Internal Server Error");
        echo '{"error":"Error en la base de datos"}';
    }

}
}
?>