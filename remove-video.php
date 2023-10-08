<?php

require './config/config.php';
require './admin/helpers/properties.php';
$title = "Eliminar video";

// iniciar sesión y verificar autorización
session_start();

$session_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
$session_username = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
$session_role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;

if (!$session_id) {
    header("Location: " . BASE_URL . "/login.php");
    exit;
}

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

$video = null;

// preparar la consulta
$stmt = $mydb->prepare("SELECT * FROM videos WHERE id = ?");
$stmt->bind_param("i", $id);

// ejecutar la consulta
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $video = $result->fetch_assoc();
}

$stmt->close();
$mydb->close();

// Si no se encontró el video, redirige al perfil del usuario
if ($video === null) {
    header("Location: " . BASE_URL . "/u/" . $session_username);
    exit;
}

$video_id =  get_video_id($video["youtube_url"]);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include './components/meta.php'; ?>
</head>

<body>
    <?php include './components/in_header.php'; ?>
    <main class="container px py" id="remove-video">
        <iframe src="https://www.youtube.com/embed/<?php echo $video_id ?>&amp;controls=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>

        <h1>¿Estás seguro de que quieres eliminar este video?</h1>

        <div class="remove-actions">
            <form action="<?php echo BASE_URL ?>/actions/delete_my_video.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $video["id"]; ?>">
                <input type="hidden" name="property_id" value="<?php echo $video["property_id"]; ?>">
                <button type="submit" class="btn btn-primary">Si, eliminar</button>
            </form>
            <a href="<?php echo BASE_URL ?>/manage-videos.php?property_id=<?php echo $video["property_id"] ?>" class="btn btn-secondary">No, cancelar</a>
        </div>
    </main>
</body>

</html>