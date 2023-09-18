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

$username = $_GET["username"];

$user = null;

// preparar la consulta
$stmt = $mydb->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username); // "s" indica que es una cadena (string)

// ejecutar la consulta
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
}

$stmt->close();
$mydb->close();

// Si no se encontró al usuario, redirige a la página de lista de usuarios.
if ($user === null) {
    header("Location: " . BASE_URL . "/admin/users.php");
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
    <main class="container px py" id="remove-user">
        <h1>¿Estás seguro de que quieres eliminar a <?php echo $user["full_name"] ?>?</h1>

        <div class="remove-actions">
            <form action="<?php echo BASE_URL ?>/admin/actions/delete_user.php" method="POST">
                <input type="hidden" name="username" value="<?php echo $user["username"]; ?>">
                <button type="submit" class="btn btn-primary">Si, eliminar usuario</button>
            </form>
            <a href="<?php echo BASE_URL ?>/admin/users.php" class="btn btn-secondary">No, cancelar</a>
        </div>
    </main>
</body>

</html>