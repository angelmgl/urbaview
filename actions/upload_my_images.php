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

$property_id = $_POST["property_id"];

// Iniciar un array para almacenar las rutas de las imágenes
$images_paths = [];

$upload_system_dir = "../uploads/properties/"; // Directorio de imágenes de propiedades
$upload_url_dir = "/uploads/properties/";

// Comprobar si realmente se enviaron imágenes
if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {

        try {
            $file = [
                'name' => $_FILES['images']['name'][$key],
                'type' => $_FILES['images']['type'][$key],
                'tmp_name' => $_FILES['images']['tmp_name'][$key],
                'error' => $_FILES['images']['error'][$key],
                'size' => $_FILES['images']['size'][$key]
            ];

            // Usamos la función upload_photo
            $final_url_path = upload_photo($file, $upload_system_dir, $upload_url_dir);

            if ($final_url_path) {
                // Obtener el ancho y alto de la imagen
                $final_system_path = $upload_system_dir . basename($final_url_path);
                list($img_width, $img_height) = getimagesize($final_system_path);

                // Insertar la ruta de la imagen en la base de datos
                $stmt = $mydb->prepare("INSERT INTO images (property_id, image_path, width, height) VALUES (?, ?, ?, ?)");

                // Asociamos los valores
                $stmt->bind_param("isii", $property_id, $final_url_path, $img_width, $img_height);

                // Ejecutamos la consulta
                if (!$stmt->execute()) {
                    // Aquí manejas cualquier error que ocurra al insertar el registro.
                    handle_form_error("No se pudo insertar la imagen número " . ($key + 1) . " en la base de datos.", array(), "/admin/images.php?property_id=" . $property_id);
                }

                $stmt->close();
            } else {
                handle_form_error("No se pudo subir la imagen número " . ($key + 1) . ".", array(), "/admin/images.php?property_id=" . $property_id);
            }
        } catch (Exception $e) {
            handle_form_error("Error con la imagen número " . ($key + 1) . ": " . $e->getMessage(), array(), "/admin/images.php?property_id=" . $property_id);
        }
    }
}

// Todos los archivos han sido procesados y almacenados.
$_SESSION['success'] = "Imágenes agregadas exitosamente";

// Cerrar la conexión a la base de datos.
$mydb->close();

header("Location: " . BASE_URL . "/manage-images.php?property_id=" . $property_id);
exit;
