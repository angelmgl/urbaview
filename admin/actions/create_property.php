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
$location = $_POST['location'];
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


// Conexión a la base de datos y preparación de la consulta.
$stmt = $mydb->prepare("
    INSERT INTO properties (title, slug, price, tour_url, user_id, property_type_id, thumbnail, rooms, bathrooms, location, department, city, neighborhood, code_ref, land_m2, land_width, land_length, build_m2, year, parking_capacity, building_floors, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param("ssisiisiisssssiiiiiiis", $title, $slug, $price, $tour_url, $user_id, $property_type_id, $thumbnail_path, $rooms, $bathrooms, $location, $department, $city, $neighborhood, $code_ref, $land_m2, $land_width, $land_length, $build_m2, $year, $parking_capacity, $building_floors, $status);

try {
    // Intenta ejecutar la consulta
    if ($stmt->execute()) {
        $_SESSION['success'] = "Propiedad agregada exitosamente";

        // Cerrar la sentencia y la conexión antes de redirigir
        $stmt->close();
        $mydb->close();

        header("Location: " . BASE_URL . "/admin/properties.php");
        exit;
    } else {
        // Si hay un error, lo manejamos
        handleFormError("Error: " . $stmt->error, array(
            'title' => $title,
            'price' => $price,
            'tour_url' => $tour_url,
            'rooms' => $rooms,
            'bathrooms' => $bathrooms,
            'location' => $location,
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
} catch (Exception $e) {
    // Esto atrapará cualquier excepción o error fatal que ocurra
    handleFormError("Error: " . $e->getMessage(), array(
        'title' => $title,
        'price' => $price,
        'tour_url' => $tour_url,
        'rooms' => $rooms,
        'bathrooms' => $bathrooms,
        'location' => $location,
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
