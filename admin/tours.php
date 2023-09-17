<?php

require '../config/config.php';
$title = "Tours";

session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
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

    <main class="container px">
        <h1>Administrar Tours</h1>
    </main>
</body>

</html>