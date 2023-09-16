<?php

session_start();

// Si el usuario ya tiene una sesión iniciada, redirige al dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: ./dashboard.php");
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
    <main id="login-page">
        <div class="container">
            <img width="80" src="../assets/img/logo.png" alt="Bienvenido a Urbaview" />
            <h1>Iniciar sesión</h1>
            <form id="login-form" action="./actions/auth_login.php" method="POST">
                <input class="custom-input" type="text" placeholder="Nombre de usuario..." name="username" required />
                <input class="custom-input" type="password" placeholder="Contraseña..." name="password" required />
                <div>
                    <input type="checkbox" name="show_password" id="show_password" />
                    <label for="show_password">Mostrar contraseña</label>
                </div>
                <div id="form-footer">
                    <input class="btn btn-primary" type="submit" value="Ingresar" />
                    <a href="#">Olvidé mi contraseña</a>
                </div>
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<p class="error">';
                    echo $_SESSION['error'];
                    echo '</p>';
                    unset($_SESSION['error']);
                }
                ?>
            </form>
        </div>
    </main>
</body>

</html>