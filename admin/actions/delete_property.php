<?php

require '../../config/config.php';

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

$property_id = $_POST['id'];

// Primero, borra registros relacionados en property_commodities
$delete_related_stmt = $mydb->prepare("DELETE FROM property_commodities WHERE property_id = ?");
$delete_related_stmt->bind_param('i', $property_id);
$delete_related_stmt->execute();
$delete_related_stmt->close();

// Después, borra la propiedad
$stmt = $mydb->prepare("DELETE FROM properties WHERE id = ?");
$stmt->bind_param('i', $property_id);
$stmt->execute();

$_SESSION['success'] = "Tour eliminado satisfactoriamente.";

$stmt->close();
$mydb->close();

// Redirige de vuelta a la lista de tours
header("Location: " . BASE_URL . "/admin/properties.php");
exit;
?>