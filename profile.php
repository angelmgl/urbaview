<?php

require './config/config.php';
require './admin/helpers/users.php';

session_start();
$username = $_GET["username"];
$user = null;
$properties = [];

// Preparar la consulta con JOIN
$query = "
    SELECT 
        u.*,
        p.*, 
        p.id AS property_id,
        i.image_path,
        i.id AS image_id,
        i.width,
        i.height
    FROM users u
    LEFT JOIN properties p ON u.id = p.user_id AND p.status = 'publicado'
    LEFT JOIN images i ON p.id = i.property_id
    WHERE u.username = ?
";

$stmt = $mydb->prepare($query);
$stmt->bind_param("s", $username);

// Ejecutar la consulta
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (!$user) {
            // Extraer detalles del usuario de la primera fila
            $user = [
                'id' => $row['id'],
                'username' => $row['username'],
                'full_name' => $row['full_name'],
                'company' => $row['company'],
                'contact_email' => $row['contact_email'],
                'whatsapp' => $row['whatsapp'],
                'instagram' => $row['instagram'],
                'facebook' => $row['facebook'],
                'is_active' => $row['is_active'],
                'role' => $row['role'],
                'profile_picture' => $row['profile_picture'],
                'properties' => []
            ];
        }

        // Si hay una propiedad asociada y no está en el array $properties
        if ($row['property_id'] && !isset($properties[$row['property_id']])) {
            $properties[$row['property_id']] = [
                'property_id' => $row['property_id'],
                'title' => $row['title'],
                'price' => $row['price'],
                'rooms' => $row['rooms'],
                'bathrooms' => $row['bathrooms'],
                'code_ref' => $row['code_ref'],
                'thumbnail' => $row['thumbnail'],
                'slug' => $row['slug'],
                'images' => []
            ];
        }

        // Si hay una imagen asociada, añadirla al array de la propiedad correspondiente
        if ($row['image_id']) {
            $properties[$row['property_id']]['images'][] = [
                'image_path' => $row['image_path'],
                'image_id' => $row['image_id'],
                'width' => $row['width'],
                'height' => $row['height'],
            ];
        }
    }

    // Añadir las propiedades al usuario
    $user['properties'] = array_values($properties);
}

$stmt->close();
$mydb->close();

if (!isset($user) || ($user['is_active'] != 1 && (!isset($_SESSION["role"]) || $_SESSION["role"] !== 'admin'))) {
    header("Location: " . BASE_URL . "/404.php");
    exit;
}

$title = $user['full_name'];

// Definir los datos que usaremos en el template

$full_name = $user['full_name'];
$company = $user['company'];
$profile_picture = get_profile_picture($user);
$contact_email = $user['contact_email'];
$facebook = $user['facebook'];
$instagram = $user['instagram'];
$whatsapp = $user['whatsapp'];

$has_contact = $contact_email || $facebook || $instagram || $whatsapp;

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include './components/meta.php'; ?>
</head>

<body>
    <?php include './components/header.php'; ?>
    <main id="profile-page">
        <section id="profile-banner" class="container">
            <div class="main-data">
                <div style="background-image: url(<?php echo $profile_picture ?>" id="profile-picture"></div>
                <div>
                    <h1 id="name"><?php echo $full_name ?></h1>
                    <h2 id="company"><?php echo $company ?></h2>
                </div>
            </div>
            <div class="aditional-data">
                <?php include './components/contact_methods.php' ?>
                <div class="details">
                    <p id="tours">Tours <span class="semibold">9</span></p>
                    <p id="views">Vistas <span class="semibold">105</span></p>
                </div>
            </div>
        </section>
        <section id="properties-listing" class="container px">
            <?php 
            if (isset($user['properties']) && !empty($user['properties'])) {
                foreach ($user['properties'] as $property) {
                    include './components/property_card.php';
                }
            } else {
                echo "<p>Este usuario no tiene propiedades publicadas...</p>";
            }?>
        </section>
    </main>
</body>

</html>