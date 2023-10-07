<?php

require '../../config/config.php';

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

// Prepara la sentencia para eliminar el video
$stmt = $mydb->prepare("DELETE FROM videos WHERE id = ?");
$stmt->bind_param('s', $_POST['id']);

// Ejecutar la sentencia
$stmt->execute();

$_SESSION['success'] = "Video eliminado satisfactoriamente.";

$stmt->close();
$mydb->close();

// Redirige de vuelta a la página de los videos
header("Location: " . BASE_URL . "/admin/videos.php?property_id=" . $_POST['property_id']);
exit;

