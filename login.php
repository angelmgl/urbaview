<?php

require './config/config.php';
session_start();

// Si el usuario ya tiene una sesión iniciada, redirige al dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/admin/dashboard.php");
    exit;
}

$title = "Iniciar sesión";

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include './components/meta.php'; ?>
</head>

<body>
    <?php include './components/header.php'; ?>
    <main id="login-page">
        <div class="container">
            <img width="50" src="<?php echo BASE_URL ?>/assets/img/logo.svg" alt="Bienvenido a Urbaview" />
            <h1>Administra tu cuenta en <span class="semibold">urbaview</span></h1>
            <form id="login-form" action="./actions/auth_login.php" method="POST">
                <div class="fields-container">
                    <input class="custom-input" type="text" placeholder="Correo" name="username" required />
                    <input class="custom-input" type="password" placeholder="Contraseña" name="password" required />
                </div>
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<p class="error">';
                    echo $_SESSION['error'];
                    echo '</p>';
                    unset($_SESSION['error']);
                }
                ?>
                <input class="btn btn-primary" type="submit" value="Ingresar" />
            </form>
            <a href="#">¿Olvidaste tu usuario o la contraseña?</a>
        </div>
    </main>
    <?php include './components/footer.php'; ?>
</body>

</html>