<?php

require '../config/config.php';
require './helpers/dates.php';
require './helpers/forms.php';
$title = "Añadir usuario";

// iniciar sesión y verificar autorización
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

    <main class="container px py">
        <div class="top-bar">
            <h1>Agregar Usuario</h1>
            <a class="btn btn-secondary" href="<?php echo BASE_URL ?>/admin/users.php">Volver</a>
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
            <form class="admin-form" action="./actions/create_user.php" method="POST" enctype="multipart/form-data">
                <div class="data-section">
                    <div class="input-wrapper text-input">
                        <label for="full_name">Nombre completo: <span class="required">*</span></label>
                        <input type="text" id="full_name" name="full_name" value="<?php echo get_form_data('full_name'); ?>" required>
                    </div>

                    <div class="input-wrapper text-input">
                        <label for="email">Email: <span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="<?php echo get_form_data('email'); ?>" required>
                    </div>

                    <div class="input-wrapper text-input">
                        <label for="password">Contraseña: <span class="required">*</span></label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <label class="cursor-pointer" for="show-password">
                        <input type="checkbox" id="show-password">
                        Mostrar contraseña
                    </label>

                    <p>
                        No olvides que una contraseña segura tiene al menos 8 caracteres.
                    </p>

                    <div class="input-wrapper text-input">
                        <label for="username">Nombre de usuario: <span class="required">*</span></label>
                        <input type="text" id="username" name="username" value="<?php echo get_form_data('username'); ?>" required>
                    </div>

                    <div class="input-wrapper text-input">
                        <label for="company">Empresa: <span class="required">*</span></label>
                        <input type="text" id="company" name="company" value="<?php echo get_form_data('company'); ?>" required>
                    </div>

                    <hr />
                    <h2>Opciones de contacto</h2>

                    <div class="input-wrapper text-input">
                        <label for="contact_email">Email de contacto:</label>
                        <input type="email" id="contact_email" name="contact_email" value="<?php echo get_form_data('contact_email'); ?>">
                    </div>

                    <div class="input-wrapper text-input">
                        <label for="whatsapp">WhatsApp:</label>
                        <input placeholder="+595991313212" type="text" id="whatsapp" name="whatsapp" value="<?php echo get_form_data('whatsapp'); ?>">
                    </div>

                    <p style="font-size: 14px;">
                        Recomendamos agregar el prefijo de país para que el botón de whatsapp funcione correctamente. Ej: +595 en el caso de Paraguay.
                    </p>

                    <div class="input-wrapper text-input">
                        <label for="instagram">Instagram:</label>
                        <input placeholder="https://instagram.com/username" type="text" id="instagram" name="instagram" value="<?php echo get_form_data('instagram'); ?>">
                    </div>

                    <div class="input-wrapper text-input">
                        <label for="facebook">Facebook:</label>
                        <input placeholder="https://facebook.com/username" type="text" id="facebook" name="facebook" value="<?php echo get_form_data('facebook'); ?>">
                    </div>
                </div>
                <div class="manage-section">
                    <div class="input-wrapper checkbox-input">
                        <label for="is_active">Activo:</label>
                        <input type="checkbox" id="is_active" name="is_active" checked>
                    </div>

                    <div class="input-wrapper select-input">
                        <label for="role">Seleccionar rol:</label>
                        <select id="role" name="role">
                            <option value="user" selected>Usuario</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>

                    <?php include './components/profile_picture_field.php' ?>

                    <input id="submit-btn" class="btn btn-primary disabled" type="submit" value="Crear Usuario" disabled>
                </div>
            </form>
            <?php unset($_SESSION['form_data']); ?>
        </section>
    </main>
    <script src="<?php echo BASE_URL ?>/admin/assets/js/users.js"></script>
    <script src="<?php echo BASE_URL ?>/admin/assets/js/password.js"></script>
</body>

</html>