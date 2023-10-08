<?php

require './config/config.php';
require './admin/helpers/properties.php';
require './admin/helpers/users.php';

session_start();

$session_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
$session_username = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
$session_role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;

$slug = $_GET['slug'];
$property = null;

// preparar la consulta
$stmt = $mydb->prepare("
    SELECT properties.id as property_id, 
    users.id as user_id, 
    property_types.id as property_type_id, 
    properties.*, 
    users.*, 
    property_types.* 
    FROM properties 
    LEFT JOIN users ON properties.user_id = users.id 
    LEFT JOIN property_types ON properties.property_type_id = property_types.id 
    WHERE properties.slug = ?
");
$stmt->bind_param("s", $slug);

// ejecutar la consulta
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $property = $result->fetch_assoc();
}

$stmt->close();

$property_id = $property['property_id'];

// recuperar las imágenes de la propiedad
$images = [];

$stmt = $mydb->prepare("
    SELECT image_path, id as image_id, width, height
    FROM images 
    WHERE property_id = ?
");
$stmt->bind_param("i", $property_id);

$stmt->execute();

$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $images[] = [
        'image_path' => $row['image_path'],
        'image_id' => $row['image_id'],
        'width' => $row['width'],
        'height' => $row['height'],
    ];
}

$property['images'] = $images;

// recuperar los videos de la propiedad
$videos = [];

$stmt = $mydb->prepare("
    SELECT youtube_url, id as video_id
    FROM videos
    WHERE property_id = ?
");
$stmt->bind_param("i", $property_id);

$stmt->execute();

$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $videos[] = [
        'youtube_url' => $row['youtube_url'],
        'video_id' => $row['video_id'],
    ];
}

$stmt->close();

$property['videos'] = $videos;

// recuperar las commodities de la propiedad
$commodities = [];

$stmt = $mydb->prepare("
    SELECT c.name
    FROM property_commodities pc
    JOIN commodities c ON pc.commodity_id = c.id
    WHERE pc.property_id = ?
");
$stmt->bind_param("i", $property_id);

$stmt->execute();

$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $commodities[] = [
        'name' => $row['name'],
    ];
}

$stmt->close();

$property['commodities'] = $commodities;

$mydb->close();

if (!isset($property) || ($property['status'] != 'publicado' && (!isset($_SESSION["role"]) || $_SESSION["role"] !== 'admin'))) {
    header("Location: " . BASE_URL . "/404.php");
    exit;
}

$title = $property['title'];
$profile_picture = get_profile_picture($property);
$contact_email = $property['contact_email'];
$facebook = $property['facebook'];
$instagram = $property['instagram'];
$whatsapp = $property['whatsapp'];

$has_contact = $contact_email || $facebook || $instagram || $whatsapp;

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include './components/meta.php'; ?>
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL ?>/assets/css/photoswipe.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
</head>

<body>
    <?php
    if ($session_id) {
        include './components/in_header.php';
    } else {
        include './components/out_header.php';
    }
    ?>
    <main id="property-page">
        <section class="iframe-container">
            <iframe src='<?php echo $property["tour_url"] ?>' frameborder='0' allowfullscreen allow='xr-spatial-tracking'></iframe>
        </section>

        <!-- INICIA SECCIÓN DE INFORMACIÓN GENERAL -->
        <section id="info-section" class="container px">
            <div class="content">
                <div class="property-info">
                    <div>
                        <span id="property_type">
                            <?php echo $property["type_name"] ?>
                            <?php if ($session_id === $property["user_id"]) { ?>
                                <a href="<?php echo BASE_URL . "/edit-my-property?slug=" . $slug ?>" class="edit-access">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z" />
                                    </svg>
                                    Editar mi tour
                                </a>
                            <?php } ?>
                        </span>
                        <h1 id="title"><?php echo $property["title"] ?></h1>
                    </div>
                    <?php include './components/details_grid.php' ?>
                </div>
                <div class="user-info">
                    <div class="share-container">
                        <p class="views">Visitas <span class="semibold">71</span></p>
                        <button class="btn share-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                                <path fill="currentColor" d="M352 224c53 0 96-43 96-96s-43-96-96-96s-96 43-96 96c0 4 .2 8 .7 11.9l-94.1 47C145.4 170.2 121.9 160 96 160c-53 0-96 43-96 96s43 96 96 96c25.9 0 49.4-10.2 66.6-26.9l94.1 47c-.5 3.9-.7 7.8-.7 11.9c0 53 43 96 96 96s96-43 96-96s-43-96-96-96c-25.9 0-49.4 10.2-66.6 26.9l-94.1-47c.5-3.9 .7-7.8 .7-11.9s-.2-8-.7-11.9l94.1-47C302.6 213.8 326.1 224 352 224z" />
                            </svg>
                            <span>Compartir Tour</span>
                        </button>
                    </div>
                    <h2 class="presented-by">Espacio presentado por:</h2>
                    <div class="user-card">
                        <div class="user-data">
                            <div style="background-image: url(<?php echo $profile_picture ?>)" id="profile-picture"></div>
                            <div>
                                <h2 id="name">
                                    <a href="<?php echo BASE_URL ?>/u/<?php echo $property["username"] ?>"><?php echo $property["full_name"] ?></a>
                                </h2>
                                <h3 id="company"><?php echo $property["company"] ?></h3>
                            </div>
                        </div>
                        <?php include './components/contact_methods.php' ?>
                    </div>
                </div>
            </div>
            <div id="accordeon">
                <button data-open="false" id="toggle-accordeon">
                    <span id="toggle-text">
                        Más info
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z" />
                    </svg>
                </button>
            </div>
        </section>
        <!-- TERMINA SECCIÓN DE INFORMACIÓN GENERAL -->

        <!-- INICIA SECCIÓN DE MULTIMEDIA -->
        <section id="media-section" class="container px">
            <div class="media">
                <div class="photos-container">
                    <h3 class="detail-title">Fotografías</h3>
                    <?php include './components/photo_slider.php' ?>
                </div>
                <div class="videos-container">
                    <h3 class="detail-title">Videos</h3>
                    <?php include './components/video_slider.php' ?>
                </div>
            </div>
            <div class="location">
                <div class="location-heading">
                    <h3 class="detail-title">Ubicación</h3>
                    <p class="code"><span class="semibold">ID:</span> <?php echo $property["code_ref"] ?></p>
                </div>
                <input type="hidden" name="lat" id="lat" value="<?php echo $property['lat']; ?>">
                <input type="hidden" name="lng" id="lng" value="<?php echo $property['lng']; ?>">
                <div id="map"></div>
            </div>
        </section>
        <!-- TERMINA SECCIÓN DE MULTIMEDIA -->
    </main>
    <script src="<?php echo BASE_URL ?>/assets/js/accordeon.js"></script>
    <script src="<?php echo BASE_URL ?>/assets/js/map.js"></script>
    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY ?>&callback=initMap&v=weekly" async></script>
</body>

</html>