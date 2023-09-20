<?php

require '../config/config.php';
require './helpers/dates.php';
require './helpers/forms.php';
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
            }
            ?>
            <form class="admin-form" action="./actions/update_user.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                <input type="hidden" name="old_photo" value="<?php echo $user['profile_picture']; ?>">

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
                        <label for="password">Contraseña: <span class="required">*</span></label>
                        <input type="password" id="password" name="password" disabled>
                    </div>

                    <div class="input-wrapper text-input">
                        <label for="username">Nombre de usuario: <span class="required">*</span></label>
                        <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
                    </div>

                    <div class="input-wrapper text-input">
                        <label for="company">Empresa: <span class="required">*</span></label>
                        <input type="text" id="company" name="company" value="<?php echo $user['company']; ?>" required>
                    </div>

                    <hr />
                    <h2>Opciones de contacto</h2>

                    <div class="input-wrapper text-input">
                        <label for="contact_email">Email de contacto:</label>
                        <input type="email" id="contact_email" name="contact_email" value="<?php echo $user['contact_email']; ?>">
                    </div>

                    <div class="input-wrapper text-input">
                        <label for="whatsapp">WhatsApp:</label>
                        <input type="text" id="whatsapp" name="whatsapp" value="<?php echo $user['whatsapp']; ?>">
                    </div>

                    <div class="input-wrapper text-input">
                        <label for="instagram">Instagram:</label>
                        <input type="text" id="instagram" name="instagram" value="<?php echo $user['instagram']; ?>">
                    </div>

                    <div class="input-wrapper text-input">
                        <label for="facebook">Facebook:</label>
                        <input type="text" id="facebook" name="facebook" value="<?php echo $user['facebook']; ?>">
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

                    <div class="input-wrapper text-input">
                        <label for="profile_picture">Foto de perfil:</label>
                        <input type="file" id="profile_picture" name="profile_picture" accept=".jpg, .jpeg, .png">
                    </div>

                    <input class="btn btn-primary" type="submit" value="Actualizar">
                </div>
            </form>
            <?php unset($_SESSION['form_data']); ?>
        </section>
    </main>
</body>

</html>