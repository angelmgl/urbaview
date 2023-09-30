<?php

require '../config/config.php';
require './helpers/properties.php';
$title = "Tours";

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

// recuperar registros de propiedades
$query = "SELECT properties.*, users.full_name FROM properties INNER JOIN users ON properties.user_id = users.id";
$result = $mydb->query($query);

$properties = [];
if ($result->num_rows > 0) {
    // Si hay resultados, recórrelos y añádelos al array $properties.
    while ($row = $result->fetch_assoc()) {
        $properties[] = $row;
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
            <h1>Administrar Tours</h1>
            <a class="btn btn-primary" href="<?php echo BASE_URL ?>/admin/add-tour.php">Añadir tour</a>
        </div>

        <?php
        if (isset($_SESSION['success'])) {
            echo '<p class="success">';
            echo $_SESSION['success'];
            echo '</p>';
            unset($_SESSION['success']);
        }
        ?>

        <?php if(count($properties) > 0) { ?>
        <section class="users-grid">
            <?php
            foreach ($properties as $property) {
                include "./components/property_card.php";
            }
            ?>
        </section>
        <?php } else { ?>
        <div>
            No hay propiedades ahora mismo
        </div>
        <?php } ?>
    </main>
</body>

</html>