<?php

require '../config/config.php';
require './helpers/properties.php';
require './helpers/dates.php';
$title = "Panel de administración";

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

// consultar por las propiedades próximas a vencer
$query = "SELECT properties.*, users.full_name 
        FROM properties 
        INNER JOIN users ON properties.user_id = users.id
        WHERE expiration_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";

$np_result = $mydb->query($query);

$next_properties = [];

// Comprobar si hay resultados y, en ese caso, añadirlos al array
if ($np_result->num_rows > 0) {
    while ($row = $np_result->fetch_assoc()) {
        $next_properties[] = $row;
    }
}

$np_result->close();

// consultar por las propiedades más populares
$query = "SELECT properties.id, properties.slug, properties.title, COUNT(property_views.id) AS total_views
        FROM properties
        LEFT JOIN property_views ON properties.id = property_views.property_id
        GROUP BY properties.id, properties.slug, properties.title
        ORDER BY total_views DESC
        LIMIT 4";

$pp_result = $mydb->query($query);

$popular_properties = [];

// Comprobar si hay resultados y, en ese caso, añadirlos al array
if ($pp_result->num_rows > 0) {
    while ($row = $pp_result->fetch_assoc()) {
        $popular_properties[] = $row;
    }
}

$pp_result->close();

// consultar por los usuarios más populares
$query = "SELECT users.id, users.username, users.full_name, COUNT(user_views.id) AS total_views
        FROM users
        LEFT JOIN user_views ON users.id = user_views.user_id
        GROUP BY users.id, users.username, users.full_name
        ORDER BY total_views DESC
        LIMIT 4";

$pu_result = $mydb->query($query);

$popular_users = [];

// Comprobar si hay resultados y, en ese caso, añadirlos al array
if ($pu_result->num_rows > 0) {
    while ($row = $pu_result->fetch_assoc()) {
        $popular_users[] = $row;
    }
}

$pu_result->close();
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
        <h1>Dashboard</h1>

        <hr />

        <section id="next-properties">
            <h2>Tours que expiran en los próximos 7 días</h2>
            <?php if (count($next_properties) > 0) { ?>
                <section class="users-grid">
                    <?php
                    foreach ($next_properties as $property) {
                        include "./components/property_card.php";
                    }
                    ?>
                </section>
            <?php } else { ?>
                <div>
                    No hay tours que expiren pronto...
                </div>
            <?php } ?>
        </section>

        <hr />

        <section id="popular">
            <div class="popular-properties">
                <h2>Tours más visitados</h2>
                <ul>
                    <li class="popular-item">
                        <span class="item-title">Título</span>
                        <span class="item-title">Vistas</span>
                    </li>
                    <?php foreach ($popular_properties as $property) { ?>
                        <li class="popular-item">
                            <a target="_blank" href="<?php echo BASE_URL . "/tour/" . $property["slug"] ?>"><?php echo $property['title'] ?></a>
                            <span><?php echo $property['total_views'] ?></span>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="popular-users">
                <h2>Perfiles más visitados</h2>
                <ul>
                    <li class="popular-item">
                        <span class="item-title">Título</span>
                        <span class="item-title">Vistas</span>
                    </li>
                    <?php foreach ($popular_users as $user) { ?>
                        <li class="popular-item">
                            <a target="_blank" href="<?php echo BASE_URL . "/u/" . $user["username"] ?>"><?php echo $user['full_name'] ?></a>
                            <span><?php echo $user['total_views'] ?></span>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </section>
    </main>
</body>

</html>