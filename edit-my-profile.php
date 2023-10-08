<?php

require './config/config.php';
require './admin/helpers/users.php';

session_start();

$session_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
$session_username = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
$session_role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;

// Si no hay sesión redirige al login
if ($session_id === null) {
    header("Location: " . BASE_URL . "/login.php");
    exit;
}

$title = "Editar mi perfil";
$user = null;

// preparar la consulta
$stmt = $mydb->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $session_username);

// ejecutar la consulta
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
}

$stmt->close();
$mydb->close();

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include './components/meta.php'; ?>
</head>

<body>
    <?php include './components/in_header.php' ?>
    <main class="container px py" id="edit-profile">
        <h1>Editar mi perfil</h1>
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
        <form class="custom-form" action="./actions/update_my_profile.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="user_id" name="user_id" value="<?php echo $user['id']; ?>">
            <input type="hidden" id="old_photo" name="old_photo" value="<?php echo $user['profile_picture']; ?>">

            <div class="data-section">
                <div class="input-wrapper text-input">
                    <label for="full_name">Nombre completo: <span class="required">*</span></label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo $user['full_name']; ?>" required>
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

                <?php include './admin/components/profile_picture_field.php' ?>

                <input id="submit-btn" class="btn btn-primary" type="submit" value="Actualizar">
            </div>
        </form>
        <?php unset($_SESSION['form_data']); ?>
    </main>
    <script src="<?php echo BASE_URL ?>/admin/assets/js/users.js"></script>
</body>

</html>