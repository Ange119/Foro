<?php




if($_SERVER["REQUEST_METHOD"]==='GET'){

}elseif($_SERVER['REQUEST_METHOD']=== 'POST'){
    usuarioMensaje();
}
function usuarioMensaje(){
global $conexion;
 

if (isset($_POST['idUsuario']) && isset($_POST['idCanal'])&&isset($_POST['mensaje'])) {
    $idUsuario = $_POST['idUsuario'];
    $idCanal = $_POST['idCanal'];
    $mensaje = $_POST['mensaje'];

    //consultamos y verificamos el nro de filas del id_usuario
    $consulta = "SELECT COUNT(*) AS total FROM usuarios WHERE id_usuario = $idUsuario";
    $resultUsu = $conexion->query($consulta);

        //consultamos y verificamos el nro de filas del id_Canal
    $consult = "SELECT COUNT(*) AS total FROM canales WHERE id_canal = $idCanal";
    $resultCanal = $conexion->query($consult);

    $filaUsu = $resultUsu->fetch_assoc();
    $filaCanal = $resultCanal->fetch_assoc();

    // Si el usuario y el canal existen, insertar el mensaje
    if ($filaUsu['total'] > 0 && $filaCanal['total'] > 0) {
        // Realizar la consulta SQL para insertar el mensaje
        $mensaje = "INSERT INTO mensajes (id_usuario, id_canal, contenido) VALUES ('$idUsuario','$idCanal','$mensaje')";
        $result = $conexion->query($mensaje);

        // Verificar si la inserción fue exitosa
        if ($result) {    
            echo '{"status":"success"}';
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo '{"error":"Error en la base de datos"}';
        }
    } else {
        header("HTTP/1.1 400 Bad Request");
        echo '{"error":"El usuario o el canal no existen"}';
    }
} else {
    header("HTTP/1.1 400 Bad Request");
    echo '{"error":"Faltan parámetros"}';
}

}
?>

