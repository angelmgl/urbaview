<?php

require '../../config/config.php';
require '../helpers/forms.php';

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

$property_id = $_POST['property_id'];
$youtube_url = $_POST['video'];

// Extraer el ID del video de YouTube de la URL proporcionada.
if (preg_match('/^https:\/\/youtu\.be\/([a-zA-Z0-9_\-]{11})\?si=([a-zA-Z0-9_\-]{16})$/', $youtube_url, $matches)) {
    $youtube_id = $matches[1];
} else {
    handle_form_error("URL de YouTube inválida.", $_POST, "/admin/videos.php?property_id=" . $property_id);
    exit;
}

// Preparar la consulta para insertar el video en la base de datos.
$stmt = $mydb->prepare("
    INSERT INTO videos (property_id, youtube_url) VALUES (?, ?);
");

$stmt->bind_param("is", $property_id, $youtube_url);

if ($stmt->execute()) {
    $_SESSION['success'] = "Video agregado exitosamente.";
    header("Location: " . BASE_URL . "/admin/videos.php?property_id=" . $property_id);
} else {
    handle_form_error("Error al guardar el video. " . $stmt->error, $_POST, "/admin/videos.php?property_id=" . $property_id);
}

$stmt->close();
$mydb->close();

