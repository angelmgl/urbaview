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
$title = $_POST['title'];
$slug = generate_slug($title);
$price = $_POST['price'];
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
$commodities = isset($_POST['commodities']) ? $_POST['commodities'] : [];

// Iniciar la variable $thumbnail_path con NULL
$thumbnail_path = NULL;

$upload_system_dir = "../../uploads/tours/"; // Asegúrate de tener este directorio creado y con permisos de escritura
$upload_url_dir = "/uploads/tours/";

// Manejar la subida de la foto de perfil
if ($_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['thumbnail']['tmp_name'];
    
    $file_extension = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
    $filename_without_extension = pathinfo($_FILES['thumbnail']['name'], PATHINFO_FILENAME);
    
    $new_name = $filename_without_extension . "_" . time() . "." . $file_extension;
    
    $final_system_path = $upload_system_dir . $new_name; 
    $final_url_path = $upload_url_dir . $new_name; 

    if (move_uploaded_file($tmp_name, $final_system_path)) {
        $thumbnail_path = $final_url_path;
    } else {
        handleFormError("No se pudo subir la imagen de perfil.", $_POST, "/admin/add-user.php");
    }
}

// Iniciar una transacción
$mydb->begin_transaction();

try {
    // Conexión a la base de datos y preparación de la consulta.
    $stmt = $mydb->prepare("
        INSERT INTO properties (title, slug, price, tour_url, user_id, property_type_id, thumbnail, rooms, bathrooms, lat, lng, department, city, neighborhood, code_ref, land_m2, land_width, land_length, build_m2, year, parking_capacity, building_floors, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->bind_param("ssisiisiiddssssiiiiiiis", $title, $slug, $price, $tour_url, $user_id, $property_type_id, $thumbnail_path, $rooms, $bathrooms, $lat, $lng, $department, $city, $neighborhood, $code_ref, $land_m2, $land_width, $land_length, $build_m2, $year, $parking_capacity, $building_floors, $status);

    if (!$stmt->execute()) {
        throw new Exception("Error al insertar la propiedad: " . $stmt->error);
    }

    $property_id = $stmt->insert_id;  // Obtener el ID del registro recién insertado.

    foreach ($commodities as $commodity_id) {
        $commodity_stmt = $mydb->prepare("INSERT INTO property_commodities (property_id, commodity_id) VALUES (?, ?)");
        $commodity_stmt->bind_param("ii", $property_id, $commodity_id);
        
        if (!$commodity_stmt->execute()) {
            throw new Exception("Error al insertar comodidad: " . $commodity_stmt->error);
        }
        
        $commodity_stmt->close();
    }

    // Si todo ha ido bien, confirmamos la transacción
    $mydb->commit();

    $_SESSION['success'] = "Propiedad agregada exitosamente";
    header("Location: " . BASE_URL . "/admin/properties.php");
    exit;
    
} catch (Exception $e) {
    $mydb->rollback();  // Revertimos las operaciones realizadas durante la transacción

    handleFormError($e->getMessage(), array(
        'title' => $title,
        'price' => $price,
        'tour_url' => $tour_url,
        'rooms' => $rooms,
        'bathrooms' => $bathrooms,
        'lat' => $lat,
        'lng' => $lng,
        'department' => $department,
        'city' => $city,
        'neighborhood' => $neighborhood,
        'code_ref' => $code_ref,
        'land_m2' => $land_m2,
        'land_width' => $land_width,
        'land_length' => $land_length,
        'build_m2' => $build_m2,
        'year' => $year,
        'parking_capacity' => $parking_capacity,
        'building_floors' => $building_floors,
    ), "/admin/add-property.php");
}

$stmt->close();
$mydb->close();
