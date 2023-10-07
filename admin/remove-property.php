<?php

require '../config/config.php';
$title = "Eliminar tour";

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

$slug = $_GET["slug"];

$property = null;

// preparar la consulta
$stmt = $mydb->prepare("SELECT * FROM properties WHERE slug = ?");
$stmt->bind_param("s", $slug);

// ejecutar la consulta
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $property = $result->fetch_assoc();
}

$stmt->close();
$mydb->close();

// Si no se encontró al usuario, redirige a la página de lista de usuarios.
if ($property === null) {
    header("Location: " . BASE_URL . "/admin/properties.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include './components/meta.php'; ?>
</head>

<body>
    <?php include './components/header.php'; ?>
    <main class="container px py" id="remove-property">
        <h1>¿Estás seguro de que quieres eliminar <?php echo $property["title"] ?>?</h1>

        <p>
            ¡Atención! Eliminar un tour es una acción irreversible. Si no estás completamente
            seguro, considera ponerlo en borrador para que ya no sea público en lugar de eliminarlo
            permanentemente. Puedes
            <a href="<?php echo BASE_URL ?>/admin/edit-property?slug=<?php echo $property["slug"]; ?>" class="semibold text-purple">
                ponerlo en borrador aquí.
            </a>
        </p>

        <div class="remove-actions">
            <form action="<?php echo BASE_URL ?>/admin/actions/delete_property.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $property["id"]; ?>">
                <button type="submit" class="btn btn-primary">Si, eliminar</button>
            </form>
            <a href="<?php echo BASE_URL ?>/admin/properties.php" class="btn btn-secondary">No, cancelar</a>
        </div>
    </main>
</body>

</html>