<?php

require '../../config/config.php';

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

$option_table = $_POST["option_table"];

// Lista de tablas permitidas para evitar inyección SQL.
$allowed_tables = ["property_types", "commodities"]; 

if (!in_array($option_table, $allowed_tables)) {
    // La tabla no está en la lista de tablas permitidas.
    header("Location: " . BASE_URL . "/admin/options.php");
    exit;
}

// Prepara la sentencia para eliminar el usuario
$stmt = $mydb->prepare("DELETE FROM $option_table WHERE id = ?");
$stmt->bind_param('i', $_POST['id']);

// Ejecutar la sentencia
$stmt->execute();

$_SESSION['success'] = "Opción eliminada satisfactoriamente.";

$stmt->close();
$mydb->close();

// Redirige de vuelta a la lista de opciones
header("Location: " . BASE_URL . "/admin/options.php");
exit;

