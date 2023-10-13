<?php

require '../../config/config.php';

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

$property_id = $_POST['id'];

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