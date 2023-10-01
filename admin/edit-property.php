<?php

require '../config/config.php';
require './helpers/forms.php';
require './helpers/properties.php';
$title = "Editar tour";

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

// Si no se encontró al usuario, redirige a la página de lista de usuarios.
if ($property === null) {
    header("Location: " . BASE_URL . "/admin/properties.php");

    $stmt->close();
    $mydb->close();
    exit;
}

// recuperar registros de usuarios
$users_query = "SELECT * FROM  users WHERE is_active = true";
$users_result = $mydb->query($users_query);

$users = [];
if ($users_result->num_rows > 0) {
    // Si hay resultados, recórrelos y añádelos al array $users.
    while ($row = $users_result->fetch_assoc()) {
        $users[] = $row;
    }
}

// recuperar registros de tipos de propiedades
$types_query = "SELECT * FROM  property_types";
$types_result = $mydb->query($types_query);

$types = [];
if ($types_result->num_rows > 0) {
    // Si hay resultados, recórrelos y añádelos al array $types.
    while ($row = $types_result->fetch_assoc()) {
        $types[] = $row;
    }
}

$stmt->close();
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
            <h1>Editar Tour</h1>
            <a class="btn btn-secondary" href="<?php echo BASE_URL ?>/admin/properties.php">Volver</a>
        </div>

        <section>
            <?php
            if (isset($_SESSION['error'])) {
                echo '<p class="error">';
                echo $_SESSION['error'];
                echo '</p>';
                unset($_SESSION['error']);
            } else if (isset($_SESSION['success'])) {
                echo '<p class="success">';
                echo $_SESSION['success'];
                echo '</p>';
                unset($_SESSION['success']);
            }
            ?>
            <form class="admin-form" action="./actions/update_property.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="property_id" name="property_id" value="<?php echo $property['id']; ?>">
                <input type="hidden" id="old_photo" name="old_photo" value="<?php echo $property['thumbnail']; ?>">

                <div class="data-section">
                    <div class="input-wrapper text-input">
                        <label for="title">Título: <span class="required">*</span></label>
                        <input type="text" id="title" name="title" value="<?php echo $property['title']; ?>" required>
                    </div>

                    <div class="input-wrapper text-input">
                        <label for="tour_url">URL del tour: <span class="required">*</span></label>
                        <input type="text" id="tour_url" name="tour_url" value="<?php echo $property['tour_url']; ?>" required>
                    </div>

                    <div class="grid cols-2">
                        <div class="input-wrapper text-input">
                            <label for="price">Precio: <span class="required">*</span></label>
                            <input type="number" id="price" name="price" value="<?php echo $property['price']; ?>" required>
                        </div>

                        <div class="input-wrapper text-input">
                            <label for="code_ref">Código de referencia:</label>
                            <input type="text" id="code_ref" name="code_ref" value="<?php echo $property['code_ref']; ?>" required>
                        </div>
                    </div>

                    <h2>Detalles de ubicación</h2>

                    <div class="input-wrapper text-input">
                        <label for="location">URL ubicación de Google Maps:</label>
                        <input type="text" id="location" name="location" value="<?php echo $property['location']; ?>">
                    </div>

                    <div class="grid cols-3">
                        <div class="input-wrapper text-input">
                            <label for="department">Departamento:</label>
                            <input type="text" id="department" name="department" value="<?php echo $property['department']; ?>">
                        </div>

                        <div class="input-wrapper text-input">
                            <label for="city">Ciudad:</label>
                            <input type="text" id="city" name="city" value="<?php echo $property['city']; ?>">
                        </div>

                        <div class="input-wrapper text-input">
                            <label for="neighborhood">Barrio:</label>
                            <input type="text" id="neighborhood" name="neighborhood" value="<?php echo $property['neighborhood']; ?>">
                        </div>
                    </div>

                    <h2>Detalles de la propiedad</h2>

                    <div class="grid cols-3">
                        <div class="input-wrapper text-input">
                            <label for="rooms">Habitaciones:</label>
                            <input type="number" id="rooms" name="rooms" value="<?php echo $property['rooms']; ?>">
                        </div>

                        <div class="input-wrapper text-input">
                            <label for="bathrooms">Baños:</label>
                            <input type="number" id="bathrooms" name="bathrooms" value="<?php echo $property['bathrooms']; ?>">
                        </div>

                        <div class="input-wrapper text-input">
                            <label for="building_floors">Pisos:</label>
                            <input type="number" id="building_floors" name="building_floors" value="<?php echo $property['building_floors']; ?>">
                        </div>
                    </div>

                    <div class="grid cols-3">
                        <div class="input-wrapper text-input">
                            <label for="land_m2">Terreno (m2):</label>
                            <input type="number" id="land_m2" name="land_m2" value="<?php echo $property['land_m2']; ?>">
                        </div>

                        <div class="input-wrapper text-input">
                            <label for="land_width">Ancho del terreno (m):</label>
                            <input type="number" id="land_width" name="land_width" value="<?php echo $property['land_width']; ?>">
                        </div>

                        <div class="input-wrapper text-input">
                            <label for="land_length">Largo del terreno (m):</label>
                            <input type="number" id="land_length" name="land_length" value="<?php echo $property['land_length']; ?>">
                        </div>
                    </div>

                    <div class="grid cols-3">
                        <div class="input-wrapper text-input">
                            <label for="build_m2">Metros construidos (m2):</label>
                            <input type="number" id="build_m2" name="build_m2" value="<?php echo $property['build_m2']; ?>">
                        </div>

                        <div class="input-wrapper text-input">
                            <label for="year">Año de la propiedad:</label>
                            <input type="number" id="year" name="year" value="<?php echo $property['year']; ?>">
                        </div>

                        <div class="input-wrapper text-input">
                            <label for="parking_capacity">Estacionamiento:</label>
                            <input type="number" id="parking_capacity" name="parking_capacity" value="<?php echo $property['parking_capacity']; ?>">
                        </div>
                    </div>
                </div>
                <div class="manage-section">
                    <div class="input-wrapper select-input">
                        <label for="status">Seleccionar estado:</label>
                        <select id="status" name="status">
                            <option value="borrador" <?php echo ($property['status'] == 'borrador') ? 'selected' : ''; ?>>Borrador</option>
                            <option value="publicado" <?php echo ($property['status'] == 'publicado') ? 'selected' : ''; ?>>Publicado</option>
                        </select>
                    </div>

                    <div class="input-wrapper select-input">
                        <label for="user_id">Seleccionar usuario:</label>
                        <select id="user_id" name="user_id">
                            <?php
                            foreach ($users as $user) {
                                $selected = $user["id"] == $property["user_id"] ? "selected" : "";
                                echo "<option value=\"{$user['id']}\" {$selected}>{$user['full_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="input-wrapper select-input">
                        <label for="property_type_id">Seleccionar tipo de propiedad:</label>
                        <select id="property_type_id" name="property_type_id">
                            <?php
                            foreach ($types as $type) {
                                $selected = $type["id"] == $property["property_type_id"] ? "selected" : "";
                                echo "<option value=\"{$type['id']}\" {$selected}>{$type['type_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <?php include './components/thumbnail_field.php' ?>

                    <input id="submit-btn" class="btn btn-primary" type="submit" value="Actualizar Tour">
                </div>
            </form>
            <?php unset($_SESSION['form_data']); ?>
        </section>
    </main>
    <script src="<?php echo BASE_URL ?>/admin/assets/js/properties.js"></script>
</body>

</html>