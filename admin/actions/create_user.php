<?php

require '../../config/config.php';
require '../helpers/forms.php';

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

// Recibe los datos del formulario.
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$full_name = $_POST['full_name'];
$username = $_POST['username'];
$company = $_POST['company'];
$contact_email = $_POST['contact_email'];
$whatsapp = $_POST['whatsapp'];
$instagram = $_POST['instagram'];
$facebook = $_POST['facebook'];
$is_active = isset($_POST['is_active']) ? 1 : 0;
$role = $_POST['role'];

// Iniciar la variable $profile_picture_path con NULL
$profile_picture_path = NULL;

$upload_system_dir = "../../uploads/users/"; // Asegúrate de tener este directorio creado y con permisos de escritura
$upload_url_dir = "/uploads/users/";

// Manejar la subida de la foto de perfil
if ($_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['profile_picture']['tmp_name'];
    $name = basename($_FILES['profile_picture']['name']);

    $final_system_path = $upload_system_dir . $name; // Esta es la ruta que usamos para mover el archivo
    $final_url_path = $upload_url_dir . $name; // Esta es la ruta que guardamos en la BD

    if (move_uploaded_file($tmp_name, $final_system_path)) {
        $profile_picture_path = $final_url_path;
    } else {
        handleFormError("No se pudo subir la imagen de perfil.", $_POST, "/admin/add-user.php");
    }
}

// Conexión a la base de datos y preparación de la consulta.
$stmt = $mydb->prepare("
    INSERT INTO users (email, password, full_name, username, company, contact_email, whatsapp, instagram, facebook, profile_picture, is_active, role) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param("ssssssssssis", $email, $password, $full_name, $username, $company, $contact_email, $whatsapp, $instagram, $facebook, $profile_picture_path, $is_active, $role);

try {
    // Intenta ejecutar la consulta
    if ($stmt->execute()) {
        $_SESSION['success'] = "Usuario agregado exitosamente";

        // Cerrar la sentencia y la conexión antes de redirigir
        $stmt->close();
        $mydb->close();

        header("Location: " . BASE_URL . "/admin/users.php");
        exit;
    } else {
        // Si hay un error, lo manejamos
        handleFormError("Error: " . $stmt->error, array(
            'email' => $email,
            'full_name' => $full_name,
            'username' => $username,
            'company' => $company,
            'contact_email' => $contact_email,
            'whatsapp' => $whatsapp,
            'instagram' => $instagram,
            'facebook' => $facebook,
        ), "/admin/add-user.php");
    }
} catch (Exception $e) {
    // Esto atrapará cualquier excepción o error fatal que ocurra
    handleFormError("Error: " . $e->getMessage(), array(
        'email' => $email,
        'full_name' => $full_name,
        'username' => $username,
        'company' => $company,
        'contact_email' => $contact_email,
        'whatsapp' => $whatsapp,
        'instagram' => $instagram,
        'facebook' => $facebook,
    ), "/admin/add-user.php");
}

$stmt->close();
$mydb->close();
