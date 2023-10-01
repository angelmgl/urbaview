<?php

require '../config/config.php';
require './helpers/forms.php';
$title = "Añadir tour";

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
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
            <h1>Agregar Tour</h1>
            <a class="btn btn-secondary" href="<?php echo BASE_URL ?>/admin/properties.php">Volver</a>
        </div>

        <section>
            <?php
            if (isset($_SESSION['error'])) {
                echo '<p class="error">';
                echo $_SESSION['error'];
                echo '</p>';
                unset($_SESSION['error']);
            }
            ?>
            <form class="admin-form" action="./actions/create_property.php" method="POST" enctype="multipart/form-data">
                <div class="data-section">
                    <div class="input-wrapper text-input">
                        <label for="title">Título: <span class="required">*</span></label>
                        <input type="text" id="title" name="title" value="<?php echo get_form_data('title'); ?>" required>
                    </div>

                    <div class="input-wrapper text-input">
                        <label for="tour_url">URL del tour: <span class="required">*</span></label>
                        <input type="text" id="tour_url" name="tour_url" value="<?php echo get_form_data('tour_url'); ?>" required>
                    </div>

                    <div class="grid cols-2">
                        <div class="input-wrapper text-input">
                            <label for="price">Precio: <span class="required">*</span></label>
                            <input type="number" id="price" name="price" value="<?php echo get_form_data('price'); ?>" required>
                        </div>

                        <div class="input-wrapper text-input">
                            <label for="code_ref">Código de referencia:</label>
                            <input type="text" id="code_ref" name="code_ref" value="<?php echo get_form_data('code_ref'); ?>">
                        </div>
                    </div>

                    <h2>Detalles de ubicación</h2>

                    <div class="input-wrapper text-input">
                        <label for="location">URL ubicación de Google Maps:</label>
                        <input type="text" id="location" name="location" value="<?php echo get_form_data('location'); ?>">
                    </div>

                    <div class="grid cols-3">
                        <div class="input-wrapper text-input">
                            <label for="department">Departamento:</label>
                            <input type="text" id="department" name="department" value="<?php echo get_form_data('department'); ?>">
                        </div>

                        <div class="input-wrapper text-input">
                            <label for="city">Ciudad:</label>
                            <input type="text" id="city" name="city" value="<?php echo get_form_data('city'); ?>">
                        </div>

                        <div class="input-wrapper text-input">
                            <label for="neighborhood">Barrio:</label>
                            <input type="text" id="neighborhood" name="neighborhood" value="<?php echo get_form_data('neighborhood'); ?>">
                        </div>
                    </div>

                    <h2>Detalles de la propiedad</h2>

                    <div class="grid cols-3">
                        <div class="input-wrapper text-input">
                            <label for="rooms">Habitaciones:</label>
                            <input type="number" id="rooms" name="rooms" value="<?php echo get_form_data('rooms'); ?>">
                        </div>

                        <div class="input-wrapper text-input">
                            <label for="bathrooms">Baños:</label>
                            <input type="number" id="bathrooms" name="bathrooms" value="<?php echo get_form_data('bathrooms'); ?>">
                        </div>

                        <div class="input-wrapper text-input">
                            <label for="building_floors">Pisos:</label>
                            <input type="number" id="building_floors" name="building_floors" value="<?php echo get_form_data('building_floors'); ?>">
                        </div>
                    </div>

                    <div class="grid cols-3">
                        <div class="input-wrapper text-input">
                            <label for="land_m2">Terreno (m2):</label>
                            <input type="number" id="land_m2" name="land_m2" value="<?php echo get_form_data('land_m2'); ?>">
                        </div>

                        <div class="input-wrapper text-input">
                            <label for="land_width">Ancho del terreno (m):</label>
                            <input type="number" id="land_width" name="land_width" value="<?php echo get_form_data('land_width'); ?>">
                        </div>

                        <div class="input-wrapper text-input">
                            <label for="land_length">Largo del terreno (m):</label>
                            <input type="number" id="land_length" name="land_length" value="<?php echo get_form_data('land_length'); ?>">
                        </div>
                    </div>

                    <div class="grid cols-3">
                        <div class="input-wrapper text-input">
                            <label for="build_m2">Metros construidos (m2):</label>
                            <input type="number" id="build_m2" name="build_m2" value="<?php echo get_form_data('build_m2'); ?>">
                        </div>

                        <div class="input-wrapper text-input">
                            <label for="year">Año de la propiedad:</label>
                            <input type="number" id="year" name="year" value="<?php echo get_form_data('year'); ?>">
                        </div>

                        <div class="input-wrapper text-input">
                            <label for="parking_capacity">Estacionamiento:</label>
                            <input type="number" id="parking_capacity" name="parking_capacity" value="<?php echo get_form_data('parking_capacity'); ?>">
                        </div>
                    </div>
                </div>
                <div class="manage-section">
                    <div class="input-wrapper select-input">
                        <label for="status">Seleccionar estado:</label>
                        <select id="status" name="status">
                            <option value="borrador" selected>Borrador</option>
                            <option value="publicado">Publicado</option>
                        </select>
                    </div>

                    <div class="input-wrapper select-input">
                        <label for="user_id">Seleccionar usuario:</label>
                        <select id="user_id" name="user_id">
                            <?php
                            foreach ($users as $user) {
                                echo "<option value=\"{$user['id']}\">{$user['full_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="input-wrapper select-input">
                        <label for="property_type_id">Seleccionar tipo de propiedad:</label>
                        <select id="property_type_id" name="property_type_id">
                            <?php
                            foreach ($types as $type) {
                                echo "<option value=\"{$type['id']}\">{$type['type_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <?php include './components/thumbnail_field.php' ?>

                    <input id="submit-btn" class="btn btn-primary" type="submit" value="Crear Tour">
                </div>
            </form>
            <?php unset($_SESSION['form_data']); ?>
        </section>
    </main>
    <script src="<?php echo BASE_URL ?>/admin/assets/js/properties.js"></script>
</body>

</html>