<?php

require './config/config.php';
require './admin/helpers/users.php';

session_start();
$username = $_GET["username"];

$user = null;

// preparar la consulta
$stmt = $mydb->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);

// ejecutar la consulta
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
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
            <?php include './components/property_card.php' ?>
            <?php include './components/property_card.php' ?>
            <?php include './components/property_card.php' ?>
        </section>
    </main>
</body>

</html>