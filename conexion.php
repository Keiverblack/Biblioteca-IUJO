<?php
$servidor = "localhost";
$usuario  = "root";
$clave    = "";
$bd       = "biblioteca";

$conexion = new mysqli($servidor, $usuario, $clave, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
