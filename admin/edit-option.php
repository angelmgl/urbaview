<?php

require '../config/config.php';
$title = "Editar opción";

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$option_table = $_GET["option_table"];

// Lista de tablas permitidas para evitar inyección SQL.
$allowed_tables = ["property_types", "commodities"];

if (!in_array($option_table, $allowed_tables)) {
    // La tabla no está en la lista de tablas permitidas.
    header("Location: " . BASE_URL . "/admin/options.php");
    exit;
}

$option_instance = null;

// preparar la consulta
$stmt = $mydb->prepare("SELECT * FROM $option_table WHERE id = ?");
$stmt->bind_param("i", $id);

// ejecutar la consulta
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $option_instance = $result->fetch_assoc();
}

$stmt->close();
$mydb->close();

// Si no se encontró la opción, redirige a la página de opciones.
if ($option_instance === null) {
    header("Location: " . BASE_URL . "/admin/options.php");
    exit;
}

$instance_title = isset($option_instance["name"]) && $option_instance["name"]
    ? $option_instance["name"]
    : $option_instance["type_name"];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include './components/meta.php'; ?>
</head>

<body>
    <?php include './components/header.php'; ?>
    <main class="container px py" id="remove-option">
        <div class="top-bar">
            <h1>Editar opción</h1>
            <a class="btn btn-secondary" href="<?php echo BASE_URL ?>/admin/options.php">Volver</a>
        </div>

        <form class="admin-form" action="<?php echo BASE_URL ?>/admin/actions/update_option.php" method="POST">
            <div class="data-section">
                <input type="hidden" name="id" value="<?php echo $option_instance["id"]; ?>">
                <input type="hidden" name="option_table" value="<?php echo $option_table; ?>">

                <div class="input-wrapper text-input">
                    <label for="name">Nombre de la opción: <span class="required">*</span></label>
                    <input type="text" id="name" name="name" value="<?php echo $instance_title ?>" required>
                </div>
            </div>
            <div class="manage-section">
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </main>
</body>

</html>