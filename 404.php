<?php

require './config/config.php';
session_start();

$title = "404 not found";

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include './components/meta.php'; ?>
</head>

<body>
    <?php include './components/out_header.php'; ?>

    <main id="not-found">
        <div class="container px py">
            <img width="50" src="<?php echo BASE_URL ?>/assets/img/logo.svg" alt="Bienvenido a Urbaview" />
            <h1>Esta página no existe...</h1>
            <p>
                Puede que hayas escrito mal la URL o que el contenido que buscas ya no esté disponible,
                puedes continuar con las siguientes opciones:
            </p>
            <ul>
                <li>
                    <a href="https://urbaview.net/">Volver a Inicio</a>
                </li>
                <li>
                    <?php if (isset($_SESSION['username'])) { ?>
                        <a href="<?php echo BASE_URL ?>/u/<?php echo $_SESSION['username'] ?>">Volver a tu perfil</a>
                    <?php } else { ?>
                        <a href="<?php echo BASE_URL ?>/login">Inicia sesión en tu cuenta</a>
                    <?php } ?>
                </li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
                    <li>
                        <a href="<?php echo BASE_URL ?>/admin/dashboard.php">Ir al panel de administración</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </main>

</body>

</html>