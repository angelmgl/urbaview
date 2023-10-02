<?php

require '../../config/config.php';

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

$property_id = $_POST["property_id"];

// Si no recibiste este parámetro o está vacío, puedes manejar el error aquí.

// Iniciar un array para almacenar las rutas de las imágenes
$images_paths = [];

$upload_system_dir = "../../uploads/properties/"; // Directorio de imágenes de propiedades
$upload_url_dir = "/uploads/properties/";

// Comprobar si realmente se enviaron imágenes
if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {

    foreach ($_FILES['images']['name'] as $key => $value) {

        if ($_FILES['images']['error'][$key] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['images']['tmp_name'][$key];

            $file_extension = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
            $filename_without_extension = pathinfo($_FILES['images']['name'][$key], PATHINFO_FILENAME);

            $new_name = $filename_without_extension . "_" . time() . "_" . $key . "." . $file_extension;

            $final_system_path = $upload_system_dir . $new_name;
            $final_url_path = $upload_url_dir . $new_name;

            if (move_uploaded_file($tmp_name, $final_system_path)) {
                $images_paths[] = $final_url_path;

                // Insertar la ruta de la imagen en la base de datos
                $stmt = $mydb->prepare("
                    INSERT INTO images (property_id, image_path)
                    VALUES (?, ?);
                ");

                // Asociamos los valores
                $stmt->bind_param("is", $property_id, $final_url_path);

                // Ejecutamos la consulta
                if (!$stmt->execute()) {
                    // Aquí manejas cualquier error que ocurra al insertar el registro.
                    handleFormError("No se pudo insertar la imagen número " . ($key + 1) . " en la base de datos.", array(), "/admin/images.php?property_id=" . $property_id);
                }

                $stmt->close();
            } else {
                handleFormError("No se pudo subir la imagen número " . ($key + 1) . ".", array(), "/admin/images.php?property_id=" . $property_id);
            }
        }
    }

}

// Todos los archivos han sido procesados y almacenados.
$_SESSION['success'] = "Imágenes agregadas exitosamente";

// Cerrar la conexión a la base de datos.
$mydb->close();

header("Location: " . BASE_URL . "/admin/images.php?property_id=" . $property_id);
exit;
