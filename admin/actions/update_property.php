<?php

require '../../config/config.php';
require '../helpers/forms.php';
require '../helpers/properties.php';

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

// Recibe los datos del formulario.
$property_id = $_POST['property_id']; // Asegúrate de enviar el ID de la propiedad desde el formulario.
$title = $_POST['title'];
$price_usd = isset($_POST['price_usd']) ? $_POST['price_usd'] : 0;
$price_gs = isset($_POST['price_gs']) ? $_POST['price_gs'] : 0;
$tour_url = $_POST['tour_url'];
$user_id = $_POST['user_id'];
$property_type_id = $_POST['property_type_id'];
$rooms = $_POST['rooms'];
$bathrooms = $_POST['bathrooms'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$department = $_POST['department'];
$city = $_POST['city'];
$neighborhood = $_POST['neighborhood'];
$code_ref = $_POST['code_ref'];
$land_m2 = $_POST['land_m2'];
$land_width = $_POST['land_width'];
$land_length = $_POST['land_length'];
$build_m2 = $_POST['build_m2'];
$year = $_POST['year'];
$parking_capacity = $_POST['parking_capacity'];
$building_floors = $_POST['building_floors'];
$status = $_POST['status'];
$expiration_date = $_POST['expiration_date'];
$old_thumbnail = $_POST['old_photo'];

$upload_system_dir = "../../uploads/tours/";
$upload_url_dir = "/uploads/tours/";

// Manejar la subida de la foto destacada
try {
    if (isset($_FILES['thumbnail'])) {
        $thumbnail_path = upload_photo($_FILES['thumbnail'], $upload_system_dir, $upload_url_dir);
    }
} catch (Exception $e) {
    handle_form_error($e->getMessage(), $_POST, "/admin/edit-property.php");
}

$thumbnail_path = $thumbnail_path ? $thumbnail_path : $old_thumbnail;

// Conexión a la base de datos y preparación de la consulta.
$stmt = $mydb->prepare("
    UPDATE properties SET title = ?, price_usd = ?, price_gs = ?, tour_url = ?, user_id = ?, property_type_id = ?, thumbnail = ?, rooms = ?, bathrooms = ?, lat = ?, lng = ?, department = ?, city = ?, neighborhood = ?, code_ref = ?, land_m2 = ?, land_width = ?, land_length = ?, build_m2 = ?, year = ?, parking_capacity = ?, building_floors = ?, status = ?, expiration_date = ? 
    WHERE id = ?
");

$stmt->bind_param("siisiisiiddssssiiiiiiissi", $title, $price_usd, $price_gs, $tour_url, $user_id, $property_type_id, $thumbnail_path, $rooms, $bathrooms, $lat, $lng, $department, $city, $neighborhood, $code_ref, $land_m2, $land_width, $land_length, $build_m2, $year, $parking_capacity, $building_floors, $status, $expiration_date, $property_id);

try {
    if ($stmt->execute()) {
        $_SESSION['success'] = "Propiedad actualizada exitosamente";
        $stmt->close();

        // Manejar la actualización de las commodities

        // 1. Obtener las commodities actuales de la propiedad
        $current_property_commodities_query = "SELECT commodity_id FROM property_commodities WHERE property_id = ?";
        $pc_stmt = $mydb->prepare($current_property_commodities_query);
        $pc_stmt->bind_param("i", $property_id);
        $pc_stmt->execute();
        $property_commodities_result = $pc_stmt->get_result();
        $current_property_commodities = [];
        while ($row = $property_commodities_result->fetch_assoc()) {
            $current_property_commodities[] = $row['commodity_id'];
        }
        $pc_stmt->close();

        // 2. Determinar qué agregar y qué eliminar
        $form_commodities = isset($_POST['commodities']) && !empty($_POST['commodities']) ? $_POST['commodities'] : [];
        $toAdd = array_diff($form_commodities, $current_property_commodities);
        $toRemove = array_diff($current_property_commodities, $form_commodities);

        // 3. Agregar los registros nuevos
        foreach ($toAdd as $commodity_id) {
            $stmt = $mydb->prepare("INSERT INTO property_commodities (property_id, commodity_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $property_id, $commodity_id);
            $stmt->execute();
            $stmt->close();
        }

        // 4. Eliminar registros
        foreach ($toRemove as $commodity_id) {
            $stmt = $mydb->prepare("DELETE FROM property_commodities WHERE property_id = ? AND commodity_id = ?");
            $stmt->bind_param("ii", $property_id, $commodity_id);
            $stmt->execute();
            $stmt->close();
        }

        $mydb->close();
        header("Location: " . BASE_URL . "/admin/properties.php");
        exit;
    } else {
        handle_form_error("Error: " . $stmt->error, $_POST, "/admin/edit-property.php?id=" . $property_id);
    }
} catch (Exception $e) {
    handle_form_error("Error: " . $e->getMessage(), $_POST, "/admin/edit-property.php?id=" . $property_id);
}

$stmt->close();
$mydb->close();
