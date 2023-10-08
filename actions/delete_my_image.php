<?php

require '../config/config.php';

// iniciar sesión y verificar autorización
session_start();

$session_user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
$session_username = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
$session_role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;

if (!$session_user_id) {
    header("Location: " . BASE_URL . "/login.php");
    exit;
}

// Prepara la sentencia para eliminar la imagen
$stmt = $mydb->prepare("DELETE FROM images WHERE id = ?");
$stmt->bind_param('s', $_POST['id']);

// Ejecutar la sentencia
$stmt->execute();

$_SESSION['success'] = "Imagen eliminada satisfactoriamente.";

$stmt->close();
$mydb->close();

// Redirige de vuelta a la lista de imagenes
header("Location: " . BASE_URL . "/manage-images.php?property_id=" . $_POST['property_id']);
exit;
