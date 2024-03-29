<?php
$server="localhost";
$usuario="root";
$pass="";
$bbdd="foro";
$conexion=new mysqli($server,$usuario,$pass,$bbdd);
if($conexion->connect_error){
    die($conexion->connect_error);

}



?>