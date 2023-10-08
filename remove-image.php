<?php

require './config/config.php';
$title = "Eliminar imagen";

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

$image = null;

// preparar la consulta
$stmt = $mydb->prepare("SELECT * FROM images WHERE id = ?");
$stmt->bind_param("i", $id);

// ejecutar la consulta
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $image = $result->fetch_assoc();
}

$stmt->close();
$mydb->close();

// Si no se encontró la imagen, redirige al perfil del usuario
if ($image === null) {
    header("Location: " . BASE_URL . "/u/" . $session_username);
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include './components/meta.php'; ?>
</head>

<body>
    <?php include './components/in_header.php'; ?>
    <main class="container px py" id="remove-image">
        <img src="<?php echo BASE_URL . $image["image_path"] ?>" class="image-to-remove" />

        <h1>¿Estás seguro de que quieres eliminar esta imagen?</h1>

        <div class="remove-actions">
            <form action="<?php echo BASE_URL ?>/actions/delete_my_image.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $image["id"]; ?>">
                <input type="hidden" name="property_id" value="<?php echo $image["property_id"]; ?>">
                <button type="submit" class="btn btn-primary">Si, eliminar</button>
            </form>
            <a href="<?php echo BASE_URL ?>/manage-images.php?property_id=<?php echo $image["property_id"] ?>" class="btn btn-secondary">No, cancelar</a>
        </div>
    </main>
</body>

</html>