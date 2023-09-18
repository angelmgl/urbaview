<?php

require '../config/config.php';
require './helpers/dates.php';
$title = "Usuarios";

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

// recuperar registros de usuarios
$query = "SELECT * FROM users";
$result = $mydb->query($query);

$users = [];
if ($result->num_rows > 0) {
    // Si hay resultados, recórrelos y añádelos al array $users.
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
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
        <div class="top-bar">
            <h1>Administrar Usuarios</h1>
            <a class="btn btn-primary" href="#">Añadir usuario</a>
        </div>

        <section class="users-grid">
            <?php
            foreach ($users as $user) {
                include './components/user_card.php';
            }
            ?>
        </section>
    </main>
</body>

</html>