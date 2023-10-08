<?php

require '../../config/config.php';

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

$option_table = $_POST["option_table"];
$new_name = $_POST["name"];

// Lista de tablas permitidas para evitar inyección SQL.
$allowed_tables = ["property_types", "commodities"]; 

$column_name = $option_table == "property_types" ? "type_name" : "name";

if (!in_array($option_table, $allowed_tables)) {
    // La tabla no está en la lista de tablas permitidas.
    header("Location: " . BASE_URL . "/admin/options.php");
    exit;
}

// Prepara la sentencia para eliminar el usuario
$stmt = $mydb->prepare("UPDATE $option_table SET $column_name = ? WHERE id = ?");
$stmt->bind_param('si', $new_name, $_POST['id']);

// Ejecutar la sentencia
$stmt->execute();

$_SESSION['success'] = "Opción actualizada satisfactoriamente.";

$stmt->close();
$mydb->close();

// Redirige de vuelta a la lista de opciones
header("Location: " . BASE_URL . "/admin/options.php");
exit;

