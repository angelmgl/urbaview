<?php

require '../config/config.php';
require '../admin/helpers/forms.php';

// iniciar sesión y verificar autorización
session_start();

$session_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
$session_username = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
$session_role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;

if (!$session_id) {
    header("Location: " . BASE_URL . "/login.php");
    exit;
}

$property_id = $_POST['property_id'];
$youtube_url = $_POST['video'];

// Extraer el ID del video de YouTube de la URL proporcionada.
if (preg_match('/^https:\/\/youtu\.be\/([a-zA-Z0-9_\-]{11})\?si=([a-zA-Z0-9_\-]{16})$/', $youtube_url, $matches)) {
    $youtube_id = $matches[1];
} else {
    handle_form_error("URL de YouTube inválida.", $_POST, "/manage-videos.php?property_id=" . $property_id);
    exit;
}

// Preparar la consulta para insertar el video en la base de datos.
$stmt = $mydb->prepare("
    INSERT INTO videos (property_id, youtube_url) VALUES (?, ?);
");

$stmt->bind_param("is", $property_id, $youtube_url);

if ($stmt->execute()) {
    $_SESSION['success'] = "Video agregado exitosamente.";
    header("Location: " . BASE_URL . "/manage-videos.php?property_id=" . $property_id);
} else {
    handle_form_error("Error al guardar el video. " . $stmt->error, $_POST, "/manage-videos.php?property_id=" . $property_id);
}

$stmt->close();
$mydb->close();

