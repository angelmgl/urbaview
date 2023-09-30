<?php

require '../config/config.php';
$title = "Opciones";

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

// recuperar registros de tipos de propiedades
$property_types_query = "SELECT * FROM property_types";
$property_types_result = $mydb->query($property_types_query);

$property_types = [];
if ($property_types_result->num_rows > 0) {
    // Si hay resultados, recórrelos y añádelos al array $property_types.
    while ($row = $property_types_result->fetch_assoc()) {
        $property_types[] = $row;
    }
}

// recuperar registros de comodities
$commodities_query = "SELECT * FROM commodities";
$commodities_result = $mydb->query($commodities_query);

$commodities = [];
if ($commodities_result->num_rows > 0) {
    // Si hay resultados, recórrelos y añádelos al array $commodities.
    while ($row = $commodities_result->fetch_assoc()) {
        $commodities[] = $row;
    }
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
        <h1>Administrar Opciones</h1>

        <?php
        if (isset($_SESSION['success'])) {
            echo '<p class="success">';
            echo $_SESSION['success'];
            echo '</p>';
            unset($_SESSION['success']);
        }
        ?>

        <div class="options-container">
            <section id="property_types" class="items-list">
                <h2>Tipos de propiedades</h2>
                <form class="add-option-form" method="POST" action="./actions/create_property_type.php">
                    <input type="text" name="type_name" id="type_name" placeholder="Agregar nuevo..." required />
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </form>
                <?php if (count($property_types) > 0) { ?>
                    <ul class="options-list">
                        <?php
                        foreach ($property_types as $type) {
                            include "./components/property_type_item.php";
                        }
                        ?>
                    </ul>
                <?php } else { ?>
                    <div>
                        No hay tipos de propiedades ahora mismo
                    </div>
                <?php } ?>
            </section>
            <section id="commodities" class="items-list">
                <h2>Commodities</h2>
                <form class="add-option-form" method="POST" action="./actions/create_commodity.php">
                    <input type="text" name="name" id="name" placeholder="Agregar nuevo..." required />
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </form>
                <?php if (count($commodities) > 0) { ?>
                    <ul class="options-list">
                        <?php
                        foreach ($commodities as $commodity) {
                            include "./components/commodity_item.php";
                        }
                        ?>
                    </ul>
                <?php } else { ?>
                    <div>
                        No hay comodidades ahora mismo
                    </div>
                <?php } ?>
            </section>
        </div>
    </main>
</body>

</html>