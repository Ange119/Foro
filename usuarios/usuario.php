<?php
require_once "../utils/conexion.php";
if ($_SERVER['REQUEST_METHOD'] === "GET") {
    
    $nombre_usuario = isset($_GET['nombre']) ? $_GET['nombre'] : null;
    
    
    if ($nombre_usuario !== null) {
        
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$nombre_usuario'";
        $result = $conexion->query($sql);

        if ($result->num_rows > 0) {
            
            while ($row = $result->fetch_assoc()) {
                echo "Nombre: " . $row["nombre_usuario"] . "<br>";
                echo "Puntuacion: " . $row["puntuacion"] . "<br>";

                
                $usuario_id = $row["id_usuario"];
                $sql_mensajes = "SELECT * FROM mensajes WHERE id_usuario = '$usuario_id'";
                $result_mensajes = $conexion->query($sql_mensajes);

                if ($result_mensajes->num_rows > 0) {
                    
                    while ($row_mensaje = $result_mensajes->fetch_assoc()) {
                        echo "Mensaje: " . $row_mensaje["contenido"] ."<br>".  "Canal " . $row_mensaje["id_canal"] . "<br>";
                        
                    }
                } else {
                    echo "El usuario no tiene mensajes.";
                }
            }
        } else {
            echo "Usuario no encontrado.";
        }
    } else {
        
        header("HTTP/1.1 400 Bad Request");
        echo '{"error": "Nombre de usuario no proporcionado"}';
    }
} elseif($_SERVER['REQUEST_METHOD']==="POST"){
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $rol = isset($_POST['rol']) ? $_POST['rol'] : null;

    // Verificar si se recibieron los datos necesarios
    if ($nombre !== null && $rol !== null) {
        // Llamar a la función insertarUsuario con los datos del usuario
        $resultado = insertarUsuario($nombre, $rol);
        // Devolver la respuesta al cliente
        echo $resultado;
    } else {
        // Si faltan datos, devolver un mensaje de error
        header("HTTP/1.1 400 Bad Request");
        echo '{"error": "Datos incompletos"}';
    }
} else {
    // Si no se recibió una solicitud POST, devolver un mensaje de error
    header("HTTP/1.1 405 Method Not Allowed");
    echo '{"error": "Método no permitido"}';
}

// function insertarUsuario(){
//     global $conexion;
//     if(!empty($_POST['nombre'])&&!empty($_POST['rol'])&&!empty($_POST['id'])){
        
//         $nombre=$_POST['nombre'];
       
//        $rol=$_POST['rol'];
//        $id=$_POST['id'];
//       $result=$conexion->query("INSERT INTO usuario(id_usuario)VALUES($id)");
//        $result=$conexion->query("INSERT INTO mensaje(id_usuario)VALUES($id)");
//        $result=$conexion->query("INSERT INTO canal (nombre_usuario,puntuacion,rol,id_usuario_creador)VALUES('$nombre',0,'$rol',$id)");

//        if ($result) {
//         echo '{"status":"success"}';
//     } else {
//         header("HTTP/1.1 500 Internal Server Error");
//         echo '{"error":"Error con la base de datos."}';
//     }
//     }else{

//         header("HTTP/1.1 500 Internal Server Error");
//         echo '{"error":"Datos insuficientes para crear un país."}';
//     }
// }
function insertarUsuario($nombre, $rol) {
    global $conexion;
    $sql_verificar = "SELECT COUNT(*) AS total FROM usuarios WHERE nombre_usuario = '$nombre' AND rol = '$rol'";
    $resultado_verificar = $conexion->query($sql_verificar);
    $fila_verificar = $resultado_verificar->fetch_assoc();

    if ($fila_verificar['total'] > 0) {
        return '{"status": "error", "message": "El usuario ya existe"}';
    }
    
    // Verificar si el nombre y el rol están presentes
    if (!empty($nombre) && !empty($rol)) {
        // Consulta SQL para insertar el nuevo usuario con puntuación inicial de 0
        $query = "INSERT INTO usuarios (nombre_usuario, puntuacion, rol ) VALUES ('$nombre', 0, '$rol')";
        
        // Ejecutar la consulta
        if ($conexion->query($query) === TRUE) {
            $conexion->query("ALTER TABLE canal ADD CONSTRAINT id_usuario_creador FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)");
            return '{"status": "success", "message": "Usuario insertado correctamente"}';
        } else {
            return '{"status": "error", "message": "Error al insertar el usuario: ' . $conexion->error . '"}';
        }
    } else {
        return '{"status": "error", "message": "Datos insuficientes para crear un usuario"}';
    }
}

?>