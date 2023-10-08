<?php

require '../config/config.php';
require './helpers/dates.php';
require './helpers/users.php';
$title = "Usuarios";

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

$full_name_value = isset($_GET['full_name']) ? htmlspecialchars($_GET['full_name']) : '';
$role_value = isset($_GET['role']) ? $_GET['role'] : '';
$is_active_value = isset($_GET['is_active']) ? $_GET['is_active'] : '';

// Crear SQL dinámico
$sql = "SELECT * FROM users WHERE 1=1";

if ($full_name_value) {
    $sql .= " AND full_name LIKE ?";
    $full_name_value = "%$full_name_value%";
}
if ($role_value) {
    $sql .= " AND role = ?";
}
if ($is_active_value !== '') {
    $is_active_value = $is_active_value === 'true' ? 1 : 0;
    $sql .= " AND is_active = ?";
}

$stmt = $mydb->prepare($sql);
if ($full_name_value && $role_value && $is_active_value !== '') {
    $stmt->bind_param('ssi', $full_name_value, $role_value, $is_active_value);
} elseif ($full_name_value && $role_value) {
    $stmt->bind_param('ss', $full_name_value, $role_value);
} elseif ($full_name_value && $is_active_value !== '') {
    $stmt->bind_param('si', $full_name_value, $is_active_value);
} elseif ($role_value && $is_active_value !== '') {
    $stmt->bind_param('si', $role_value, $is_active_value);
} elseif ($full_name_value) {
    $stmt->bind_param('s', $full_name_value);
} elseif ($role_value) {
    $stmt->bind_param('s', $role_value);
} elseif ($is_active_value !== '') {
    $stmt->bind_param('i', $is_active_value);
}

$stmt->execute();
$result = $stmt->get_result();

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$full_name_value_display = str_replace('%', '', $full_name_value);

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
            <a class="btn btn-primary" href="<?php echo BASE_URL ?>/admin/add-user.php">Añadir usuario</a>
        </div>

        <?php
        if (isset($_SESSION['success'])) {
            echo '<p class="success">';
            echo $_SESSION['success'];
            echo '</p>';
            unset($_SESSION['success']);
        }
        ?>

        <form method="GET" class="admin-form filters-container grid cols-4">
            <!-- buscador por nombre -->
            <div class="input-wrapper text-input">
                <label for="full_name">Nombre completo: <span class="required">*</span></label>
                <input type="text" id="full_name" name="full_name" value="<?php echo $full_name_value_display; ?>">
            </div>
            <!-- filtrar por rol -->
            <div class="input-wrapper select-input">
                <label for="role">Seleccionar rol:</label>
                <select id="role" name="role">
                    <option value="" <?php echo ($role_value == '') ? 'selected' : ''; ?>>Selecciona...</option>
                    <option value="user" <?php echo ($role_value == 'user') ? 'selected' : ''; ?>>Usuario</option>
                    <option value="admin" <?php echo ($role_value == 'admin') ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>
            <!-- filtrar por estado -->
            <div class="input-wrapper select-input">
                <label for="is_active">Seleccionar estado:</label>
                <select id="is_active" name="is_active">
                    <option value="" <?php echo ($is_active_value == '') ? 'selected' : ''; ?>>Selecciona...</option>
                    <option value="true" <?php echo ($is_active_value == 'true') ? 'selected' : ''; ?>>Activo</option>
                    <option value="false" <?php echo ($is_active_value == 'false') ? 'selected' : ''; ?>>Inactivo</option>
                </select>
            </div>
            <!-- buscar -->
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>

        <?php if (empty($users)) { ?>
            <p>No hay resultados para esta búsqueda...</p>
        <?php } else { ?>
        <section class="users-grid">
            <?php
                foreach ($users as $user) {
                    include './components/user_card.php';
                }
            ?>
        </section>
        <?php } ?>
    </main>
</body>

</html>