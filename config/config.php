<?php 

$db_host = "localhost"; 
$db_user = "root";
$db_password = "";
$db_name = "urbaview";

// Crear la conexión
$mydb = new mysqli($db_host, $db_user, $db_password, $db_name);
$mydb->set_charset('utf8mb4');

// Verificar si hay algún error en la conexión
if ($mydb->connect_error) {
    die("Error en la conexión: " . $mydb->connect_error);
}

define('BASE_URL', 'http://localhost/urbaview');
define('ROOT', __DIR__);

?>