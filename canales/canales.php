<?php
require_once "../utils/conexion.php";
header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD']==='POST'){
     if(permisoCanal()){
        insertarCanal();
    }else{
         header("HTTP/1.1 403 Forbidden");
         echo '{"error":"No tienes permiso para crear canales."}';
    }
 
}
 function permisoCanal() {
     global $conexion;
    if(!empty($_POST['id_usuario'])) {
         $id_usuario = $_POST['id_usuario'];
         // Aquí debes realizar una consulta para obtener el rol y la puntuación del usuario
        $query = "SELECT puntuacion, rol FROM usuarios WHERE id_usuario = '$id_usuario'";
         $resultado = $conexion->query($query);
         if($resultado && $resultado->num_rows > 0) {
             $usuario = $resultado->fetch_assoc();
             // Verificar las condiciones para permitir la creación del canal
             if($usuario['rol'] === 'administrador' || $usuario['puntuacion'] > 100) {
                 return true;
             }
         }
     }
     return false;
 }
function insertarCanal(){
    global $conexion;
    if(!empty($_POST['nombre'])&&!empty($_POST['descripcion'])&&!empty($_POST['id_usuario'])){
        $nombre=$_POST['nombre'];
        $descripcion=$_POST['descripcion'];
        $id=$_POST['id_usuario'];
        $result= $conexion->query("INSERT INTO mensaje (id_usuario) VALUES ('$id')");

        
        $result = $conexion->query("INSERT INTO usuarios (id_usuario) VALUES ('$id')");
        $sql="INSERT INTO canales (nombre,descripcion,id_usuario_creador)VALUES ('$nombre','$descripcion',$id);";
        $result=$conexion->query($sql);
        

            if ($result) {
                echo '{"status":"success"}';
            } else {
                header("HTTP/1.1 500 Internal Server Error");
                echo '{"error":"Error con la base de datos."}';
            }
        
    }else {
        header("HTTP/1.1 500 Internal Server Error");
        echo '{"error":"Datos insuficientes para crear un país."}';
    }
}

?>