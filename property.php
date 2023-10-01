<?php

require './config/config.php';
require './admin/helpers/properties.php';
require './admin/helpers/users.php';

session_start();
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

print_r($property);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include './components/meta.php'; ?>
</head>

<body>
    <?php include './components/header.php'; ?>
    <main id="property-page">
        <section class="iframe-container">
            <iframe src='<?php echo $property["tour_url"] ?>' frameborder='0' allowfullscreen allow='xr-spatial-tracking'></iframe>
        </section>

        <!-- INICIA SECCIÓN DE INFORMACIÓN GENERAL -->
        <section id="info-section" class="container px">
            <div class="content">
                <div class="property-info">
                    <div>
                        <span id="property_type"><?php echo $property["type_name"] ?></span>
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
                    <div class="photos-grid">
                        <img class="property-photo" src="<?php echo BASE_URL ?>/assets/img/property.jpg" alt="Casa a estrenar" />
                        <img class="property-photo" src="<?php echo BASE_URL ?>/assets/img/property.jpg" alt="Casa a estrenar" />
                        <img class="property-photo" src="<?php echo BASE_URL ?>/assets/img/property.jpg" alt="Casa a estrenar" />
                        <div class="photos-overflow">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                <path fill="currentColor" d="M0 96C0 60.7 28.7 32 64 32H448c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96zM323.8 202.5c-4.5-6.6-11.9-10.5-19.8-10.5s-15.4 3.9-19.8 10.5l-87 127.6L170.7 297c-4.6-5.7-11.5-9-18.7-9s-14.2 3.3-18.7 9l-64 80c-5.8 7.2-6.9 17.1-2.9 25.4s12.4 13.6 21.6 13.6h96 32H424c8.9 0 17.1-4.9 21.2-12.8s3.6-17.4-1.4-24.7l-120-176zM112 192a48 48 0 1 0 0-96 48 48 0 1 0 0 96z" />
                            </svg>
                            <span class="overflow-quantity">7+</span>
                        </div>
                    </div>
                </div>
                <div class="videos-container">
                    <h3 class="detail-title">Videos</h3>
                    <div class="videos-grid">
                        <div class="video">
                            <iframe src="https://www.youtube.com/embed/u31qwQUeGuM?si=5aEQRc5Scp4oQdji&amp;controls=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                        <div class="video">
                            <iframe src="https://www.youtube.com/embed/u31qwQUeGuM?si=5aEQRc5Scp4oQdji&amp;controls=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <div class="location">
                <div class="location-heading">
                    <h3 class="detail-title">Ubicación</h3>
                    <p class="code"><span class="semibold">ID:</span> <?php echo $property["code_ref"] ?></p>
                </div>
                <iframe src="<?php echo $property["location"] ?>" style="width: 100%; border: 0;" height="280" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>
        <!-- TERMINA SECCIÓN DE MULTIMEDIA -->
    </main>
    <script src="<?php echo BASE_URL ?>/assets/js/accordeon.js"></script>
</body>

</html>