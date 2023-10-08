<?php

require '../config/config.php';
require '../admin/helpers/forms.php';

// iniciar sesión y verificar autorización
session_start();

$session_user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
$session_username = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
$session_role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;

if (!$session_user_id) {
    header("Location: " . BASE_URL . "/login.php");
    exit;
}

// Recibe los datos del formulario.
$user_id = $session_user_id;
$full_name = $_POST['full_name'];
$username = $_POST['username'];
$company = $_POST['company'];
$contact_email = $_POST['contact_email'];
$whatsapp = $_POST['whatsapp'];
$instagram = $_POST['instagram'];
$facebook = $_POST['facebook'];
$old_photo = $_POST['old_photo'];

$upload_system_dir = "../uploads/users/";
$upload_url_dir = "/uploads/users/";

// Manejar la subida de la foto de perfil
try {
    if (isset($_FILES['profile_picture'])) {
        $profile_picture_path = upload_photo($_FILES['profile_picture'], $upload_system_dir, $upload_url_dir);
    }
} catch (Exception $e) {
    handle_form_error($e->getMessage(), $_POST, "/admin/edit-user.php");
}

$profile_picture_path = $profile_picture_path ? $profile_picture_path : $old_photo;

// Conexión a la base de datos y preparación de la consulta.
$stmt = $mydb->prepare("
    UPDATE users SET full_name = ?, username = ?, company = ?, contact_email = ?, whatsapp = ?, instagram = ?, facebook = ?, profile_picture = ? 
    WHERE id = ?
");

$stmt->bind_param("ssssssssi", $full_name, $username, $company, $contact_email, $whatsapp, $instagram, $facebook, $profile_picture_path, $user_id);

try {
    if ($stmt->execute()) {
        $_SESSION['success'] = "Perfil actualizado exitosamente";

        // Cerrar la sentencia y la conexión antes de redirigir
        $stmt->close();
        $mydb->close();

        header("Location: " . BASE_URL . "/edit-my-profile.php");
        exit;
    } else {
        handle_form_error("Error: " . $stmt->error, array(
            'email' => $email,
            'full_name' => $full_name,
            'username' => $username,
            'company' => $company,
            'contact_email' => $contact_email,
            'whatsapp' => $whatsapp,
            'instagram' => $instagram,
            'facebook' => $facebook,
        ), "/edit-my-profile.php");
    }
} catch (Exception $e) {
    handle_form_error("Error: " . $e->getMessage(), array(
        'email' => $email,
        'full_name' => $full_name,
        'username' => $username,
        'company' => $company,
        'contact_email' => $contact_email,
        'whatsapp' => $whatsapp,
        'instagram' => $instagram,
        'facebook' => $facebook,
    ), "/edit-my-profile.php");
}

$stmt->close();
$mydb->close();
