<?php

require '../config/config.php';
require './helpers/dates.php';
require './helpers/forms.php';
require './helpers/users.php';
$title = "Editar usuario";

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
$stmt->bind_param("s", $username);

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

    <main class="container px py">
        <div class="top-bar">
            <h1>Editar Usuario</h1>
            <a class="btn btn-secondary" href="<?php echo BASE_URL ?>/admin/users.php">Volver</a>
        </div>

        <section>
            <?php
            if (isset($_SESSION['error'])) {
                echo '<p class="error">';
                echo $_SESSION['error'];
                echo '</p>';
                unset($_SESSION['error']);
            } else if (isset($_SESSION['success'])) {
                echo '<p class="success">';
                echo $_SESSION['success'];
                echo '</p>';
                unset($_SESSION['success']);
            }
            ?>
            <form class="admin-form" action="./actions/update_user.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="user_id" name="user_id" value="<?php echo $user['id']; ?>">
                <input type="hidden" id="old_photo" name="old_photo" value="<?php echo $user['profile_picture']; ?>">

                <div class="data-section">
                    <div class="input-wrapper text-input">
                        <label for="full_name">Nombre completo: <span class="required">*</span></label>
                        <input type="text" id="full_name" name="full_name" value="<?php echo $user['full_name']; ?>" required>
                    </div>

                    <div class="input-wrapper text-input">
                        <label for="email">Email: <span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                    </div>

                    <div class="input-wrapper text-input">
                        <label for="username">Nombre de usuario: <span class="required">*</span></label>
                        <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
                    </div>

                    <div class="input-wrapper text-input">
                        <label for="company">Empresa:</label>
                        <input type="text" id="company" name="company" value="<?php echo $user['company']; ?>">
                    </div>

                    <hr />
                    <h2>Opciones de contacto</h2>

                    <div class="input-wrapper text-input">
                        <label for="contact_email">Email de contacto:</label>
                        <input type="email" id="contact_email" name="contact_email" value="<?php echo $user['contact_email']; ?>">
                    </div>

                    <div class="input-wrapper text-input">
                        <label for="whatsapp">WhatsApp:</label>
                        <input placeholder="+595991313212" type="text" id="whatsapp" name="whatsapp" value="<?php echo $user['whatsapp']; ?>">
                    </div>

                    <p style="font-size: 14px;">
                        Recomendamos agregar el prefijo de país para que el botón de whatsapp funcione correctamente. Ej: +595 en el caso de Paraguay.
                    </p>

                    <div class="input-wrapper text-input">
                        <label for="instagram">Instagram:</label>
                        <input placeholder="https://instagram.com/username" type="text" id="instagram" name="instagram" value="<?php echo $user['instagram']; ?>">
                    </div>

                    <div class="input-wrapper text-input">
                        <label for="facebook">Facebook:</label>
                        <input placeholder="https://facebook.com/username" type="text" id="facebook" name="facebook" value="<?php echo $user['facebook']; ?>">
                    </div>
                </div>
                <div class="manage-section">
                    <div class="input-wrapper checkbox-input">
                        <label for="is_active">Activo:</label>
                        <input type="checkbox" id="is_active" name="is_active" <?php echo ($user['is_active'] == 1) ? 'checked' : ''; ?>>
                    </div>

                    <div class="input-wrapper select-input">
                        <label for="role">Seleccionar rol:</label>
                        <select id="role" name="role">
                            <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>Usuario</option>
                            <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Administrador</option>
                        </select>
                    </div>

                    <?php include './components/profile_picture_field.php' ?>

                    <input id="submit-btn" class="btn btn-primary" type="submit" value="Actualizar">

                    <a class="change-password" href="<?php echo BASE_URL ?>/admin/change-password.php?username=<?php echo $user["username"]; ?>">Cambiar contraseña</a>
                </div>
            </form>
            <?php unset($_SESSION['form_data']); ?>
        </section>
    </main>
    <script src="<?php echo BASE_URL ?>/admin/assets/js/users.js"></script>
</body>

</html>