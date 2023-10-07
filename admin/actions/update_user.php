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
try {
    if (isset($_FILES['profile_picture'])) {
        $profile_picture_path = upload_photo($_FILES['profile_picture'], $upload_system_dir, $upload_url_dir);
    }
} catch (Exception $e) {
    handle_form_error($e->getMessage(), $_POST, "/admin/edit-user.php");
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

        // Cerrar la sentencia y la conexión antes de redirigir
        $stmt->close();
        $mydb->close();

        header("Location: " . BASE_URL . "/admin/users.php");
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
        ), "/admin/edit-user.php?username=" . $username);
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
    ), "/admin/edit-user.php??username=" . $username);
}

$stmt->close();
$mydb->close();
