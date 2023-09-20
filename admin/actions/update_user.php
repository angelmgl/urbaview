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
$user_id = $_POST['user_id']; // Asegúrate de enviar el ID del usuario desde el formulario.
$email = $_POST['email'];
$full_name = $_POST['full_name'];
$username = $_POST['username'];
$company = $_POST['company'];
$contact_email = $_POST['contact_email'];
$whatsapp = $_POST['whatsapp'];
$instagram = $_POST['instagram'];
$facebook = $_POST['facebook'];
$is_active = isset($_POST['is_active']) ? 1 : 0;
$role = $_POST['role'];
$old_photo = $_POST['old_photo'];

$upload_system_dir = "../../uploads/users/";
$upload_url_dir = "/uploads/users/";

$profile_picture_path = $old_photo; // Inicializamos con la imagen anterior

// Manejar la subida de la foto de perfil
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['profile_picture']['tmp_name'];
    $original_name = basename($_FILES['profile_picture']['name']);
    
    // Obtener la extensión del archivo
    $file_extension = pathinfo($original_name, PATHINFO_EXTENSION);
    
    // Crear un nuevo nombre para el archivo añadiendo una marca de tiempo
    $name = pathinfo($original_name, PATHINFO_FILENAME) . "_" . time() . "." . $file_extension;

    $final_system_path = $upload_system_dir . $name; // Esta es la ruta que usamos para mover el archivo
    $final_url_path = $upload_url_dir . $name; // Esta es la ruta que guardamos en la BD

    if (move_uploaded_file($tmp_name, $final_system_path)) {
        $profile_picture_path = $final_url_path;
    } else {
        handleFormError("No se pudo subir la imagen de perfil.", $_POST, "/admin/edit-user.php");
    }
}

// Conexión a la base de datos y preparación de la consulta.
$stmt = $mydb->prepare("
    UPDATE users SET email = ?, full_name = ?, username = ?, company = ?, contact_email = ?, whatsapp = ?, instagram = ?, facebook = ?, profile_picture = ?, is_active = ?, role = ? 
    WHERE id = ?
");

$stmt->bind_param("sssssssssisi", $email, $full_name, $username, $company, $contact_email, $whatsapp, $instagram, $facebook, $profile_picture_path, $is_active, $role, $user_id);

try {
    if ($stmt->execute()) {
        $_SESSION['success'] = "Usuario actualizado exitosamente";
        header("Location: " . BASE_URL . "/admin/users.php");
        exit;
    } else {
        handleFormError("Error: " . $stmt->error, array(
            'email' => $email,
            'full_name' => $full_name,
            'username' => $username,
            'company' => $company,
            'contact_email' => $contact_email,
            'whatsapp' => $whatsapp,
            'instagram' => $instagram,
            'facebook' => $facebook,
        ), "/admin/edit-user.php?id=" . $user_id);
    }
} catch (Exception $e) {
    handleFormError("Error: " . $e->getMessage(), array(
        'email' => $email,
        'full_name' => $full_name,
        'username' => $username,
        'company' => $company,
        'contact_email' => $contact_email,
        'whatsapp' => $whatsapp,
        'instagram' => $instagram,
        'facebook' => $facebook,
    ), "/admin/edit-user.php?id=" . $user_id);
}

$stmt->close();
$mydb->close();