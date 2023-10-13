<?php

require './config/config.php';
require './admin/helpers/users.php';
require './admin/helpers/properties.php';
require './helpers/views.php';

session_start();

$session_user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
$session_username = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
$session_role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;

$username = $_GET["username"];
$user = null;
$properties = [];

// Preparar la consulta con JOIN
$query = "
    SELECT 
        u.*,
        u.id AS xuser_id,
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
                'id' => $row['xuser_id'],
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
                'price_usd' => $row['price_usd'],
                'price_gs' => $row['price_gs'],
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

if (!isset($user) || ($user['is_active'] != 1 && (!isset($_SESSION["role"]) || $_SESSION["role"] !== 'admin'))) {
    header("Location: " . BASE_URL . "/404.php");
    exit;
}

if ($session_user_id != $user["id"]) {
    // evita que el usuario se registre vistas a si mismo
    register_view($user["id"], 'user', $mydb);
}

$total_views = get_views($user["id"], 'user', $mydb);

$mydb->close();

$title = $user['full_name'];

// Definir los datos que usaremos en el template

$full_name = $user['full_name'];
$company = $user['company'];
$profile_picture = get_profile_picture($user);
$seo_image = $profile_picture;
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
    <?php
    if ($session_user_id) {
        include './components/in_header.php';
    } else {
        include './components/out_header.php';
    }
    ?>
    <main id="profile-page">
        <section id="profile-banner" class="container">
            <div class="main-data">
                <div style="background-image: url(<?php echo $profile_picture ?>" id="profile-picture"></div>
                <div>
                    <h1 id="name"><?php echo $full_name ?></h1>
                    <h2 id="company"><?php echo $company ?></h2>
                </div>
                <?php if ($session_username === $username) { ?>
                    <a href="<?php echo BASE_URL ?>/edit-my-profile.php" class="edit-access">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                            <path fill="currentColor" d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z" />
                        </svg>
                        Editar mi perfil
                    </a>
                <?php } ?>
            </div>
            <div class="aditional-data">
                <?php include './components/contact_methods.php' ?>
                <div class="details">
                    <p id="tours">Tours <span class="semibold"><?php echo count($user['properties']) ?></span></p>
                    <p id="views">Vistas <span class="semibold"><?php echo $total_views ?></span></p>
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
            } ?>
        </section>
    </main>
</body>

</html>