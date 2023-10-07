<?php

require '../config/config.php';
require './helpers/properties.php';
$title = "Videos";

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

$property_id = $_GET["property_id"];

$property_data = [
    'title' => '',
    'slug' => '',
    'videos' => []
];

// preparar la consulta
$stmt = $mydb->prepare("
    SELECT properties.title, properties.slug, videos.youtube_url, videos.id AS video_id
    FROM properties 
    LEFT JOIN videos ON properties.id = videos.property_id 
    WHERE properties.id = ?;
");
$stmt->bind_param("i", $property_id);

// ejecutar la consulta
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $property_data['title'] = $row['title'];
        $property_data['slug'] = $row['slug'];
        if ($row['youtube_url'] !== null && $row['video_id'] !== null) { // para evitar agregar imágenes nulas
            $property_data['videos'][] = [
                'youtube_url' => $row['youtube_url'],
                'video_id' => $row['video_id']
            ];
        }
    }
}

// Si no hay título para la propiedad, asumimos que no se encontró la propiedad
if (empty($property_data['title'])) {
    header("Location: " . BASE_URL . "/admin/properties.php");
    $stmt->close();
    $mydb->close();
    exit;
}

// Cierra la conexión a la base de datos.
$mydb->close();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include './components/meta.php'; ?>
</head>

<body>
    <?php include './components/header.php'; ?>

    <main class="container px py">
        <div class="top-bar">
            <h1>Administrar videos de <?php echo $property_data['title'] ?></h1>
            <a class="btn btn-secondary" href="<?php echo BASE_URL ?>/admin/edit-property.php?slug=<?php echo $property_data['slug'] ?>">Volver a la propiedad</a>
        </div>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<p class="error">';
            echo $_SESSION['error'];
            echo '</p>';
            unset($_SESSION['error']);
        }
        ?>

        <form class="admin-form" action="./actions/save_video.php" method="POST">
            <input type="hidden" name="property_id" value="<?php echo $property_id ?>">
            <div class="video-input">
                <label>Pega la URL de tu video:</label>
                <input type="text" id="video" class="show" name="video" placeholder="https://youtu.be/xgFUC835Xlc?si=bgqrZCsW6AA2sYIj" required>
                <input id="submit-btn" class="btn btn-primary" type="submit" value="Guardar video">
            </div>
        </form>

        <?php
        if (isset($_SESSION['success'])) {
            echo '<p class="success">';
            echo $_SESSION['success'];
            echo '</p>';
            unset($_SESSION['success']);
        }
        ?>

        <?php if (!empty($property_data['videos'])) { ?>
            <section class="videos-grid">
                <?php
                foreach ($property_data['videos'] as $video) {
                    include "./components/video_container.php";
                }
                ?>
            </section>
        <?php } else { ?>
            <div>
                Esta propiedad no posee videos...
            </div>
        <?php } ?>
    </main>
</body>

</html>