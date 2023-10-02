<?php

require '../../config/config.php';

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

// Prepara la sentencia para eliminar el tour
$stmt = $mydb->prepare("DELETE FROM images WHERE id = ?");
$stmt->bind_param('s', $_POST['id']);

// Ejecutar la sentencia
$stmt->execute();

$_SESSION['success'] = "Imagen eliminada satisfactoriamente.";

$stmt->close();
$mydb->close();

// Redirige de vuelta a la lista de tours
header("Location: " . BASE_URL . "/admin/images.php?property_id=" . $_POST['property_id']);
exit;

