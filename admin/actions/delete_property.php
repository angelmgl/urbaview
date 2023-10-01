<?php

require '../../config/config.php';

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

// Prepara la sentencia para eliminar el tour
$stmt = $mydb->prepare("DELETE FROM properties WHERE slug = ?");
$stmt->bind_param('s', $_POST['slug']);

// Ejecutar la sentencia
$stmt->execute();

$_SESSION['success'] = "Tour eliminado satisfactoriamente.";

$stmt->close();
$mydb->close();

// Redirige de vuelta a la lista de tours
header("Location: " . BASE_URL . "/admin/properties.php");
exit;

