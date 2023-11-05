<?php

require '../config/config.php';
require './helpers/properties.php';
require './helpers/dates.php';
$title = "Tours";

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

$user_id_value = isset($_GET['user_id']) ? htmlspecialchars($_GET['user_id']) : '';
$property_type_id_value = isset($_GET['property_type_id']) ? $_GET['property_type_id'] : '';
$status_value = isset($_GET['status']) ? $_GET['status'] : '';

$query = "SELECT properties.*, users.full_name FROM properties INNER JOIN users ON properties.user_id = users.id WHERE 1=1"; 

// Anexar condiciones al query según los filtros
if (!empty($user_id_value)) {
    $query .= " AND properties.user_id = $user_id_value";
}
if (!empty($property_type_id_value)) {
    $query .= " AND properties.property_type_id = $property_type_id_value";
}
if (!empty($status_value)) {
    $query .= " AND properties.status = '$status_value'";
}

$result = $mydb->query($query);

$properties = [];
if ($result->num_rows > 0) {
    // Si hay resultados, recórrelos y añádelos al array $properties.
    while ($row = $result->fetch_assoc()) {
        $properties[] = $row;
    }
}

// recuperar registros de usuarios que tienen al menos una propiedad asociada
$users_query = "SELECT users.* 
                FROM users 
                INNER JOIN properties ON users.id = properties.user_id 
                WHERE users.is_active = true 
                GROUP BY users.id
                ORDER BY users.full_name ASC";
$users_result = $mydb->query($users_query);

$users = [];
while ($row = $users_result->fetch_assoc()) {
    $users[] = $row;
}

// recuperar registros de tipos de propiedades
$types_query = "SELECT * FROM  property_types";
$types_result = $mydb->query($types_query);

$types = [];
while ($row = $types_result->fetch_assoc()) {
    $types[] = $row;
}

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
            <h1>Administrar Tours</h1>
            <a class="btn btn-primary" href="<?php echo BASE_URL ?>/admin/add-property.php">Añadir tour</a>
        </div>

        <?php
        if (isset($_SESSION['success'])) {
            echo '<p class="success">';
            echo $_SESSION['success'];
            echo '</p>';
            unset($_SESSION['success']);
        }
        ?>

        <form method="GET" class="admin-form filters-container grid cols-4">
            <!-- filtrar por usuario -->
            <div class="input-wrapper select-input">
                <label for="user_id">Por usuario:</label>
                <select id="user_id" name="user_id">
                    <option value="" <?php echo ($user_id_value == '') ? 'selected' : ''; ?>>Selecciona...</option>
                    <?php
                    foreach ($users as $user) {
                        $selected = ($user_id_value == $user['id']) ? "selected" : "";
                        echo "<option value=\"{$user['id']}\" {$selected}>{$user['full_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <!-- filtrar por tipo -->
            <div class="input-wrapper select-input">
                <label for="property_type_id">Tipo de propiedad:</label>
                <select id="property_type_id" name="property_type_id">
                    <option value="" <?php echo ($property_type_id_value == '') ? 'selected' : ''; ?>>Selecciona...</option>
                    <?php
                    foreach ($types as $type) {
                        $selected = ($property_type_id_value == $type['id']) ? "selected" : "";
                        echo "<option value=\"{$type['id']}\" {$selected}>{$type['type_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <!-- filtrar por estado -->
            <div class="input-wrapper select-input">
                <label for="status">Seleccionar estado:</label>
                <select id="status" name="status">
                    <option value="" <?php echo ($status_value == '') ? 'selected' : ''; ?>>Selecciona...</option>
                    <option value="publicado" <?php echo ($status_value == 'publicado') ? 'selected' : ''; ?>>Publicado</option>
                    <option value="borrador" <?php echo ($status_value == 'borrador') ? 'selected' : ''; ?>>Borrador</option>
                </select>
            </div>
            <!-- buscar -->
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>

        <?php if (count($properties) > 0) { ?>
            <section class="users-grid">
                <?php
                foreach ($properties as $property) {
                    include "./components/property_card.php";
                }
                ?>
            </section>
        <?php } else { ?>
            <div>
                No se encontraron propiedades...
            </div>
        <?php } ?>
    </main>
</body>

</html>