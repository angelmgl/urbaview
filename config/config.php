<?php 

$db_host = "localhost"; 
$db_user = "root";
$db_password = "";
$db_name = "urbaview";

// Crear la conexión
$mydb = new mysqli($db_host, $db_user, $db_password, $db_name);

// Verificar si hay algún error en la conexión
if ($mydb->connect_error) {
    die("Error en la conexión: " . $mydb->connect_error);
}

?>