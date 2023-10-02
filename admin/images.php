<?php

require '../config/config.php';
$title = "Imágenes";

// iniciar sesión y verificar autorización
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

$property_id = $_GET["property_id"];

$property_data = [
    'title' => '',
    'slug' => '',
    'images' => []
];

// preparar la consulta
$stmt = $mydb->prepare("
    SELECT properties.title, properties.slug, images.image_path, images.id AS image_id
    FROM properties 
    LEFT JOIN images ON properties.id = images.property_id 
    WHERE properties.id = ?;
");
$stmt->bind_param("i", $property_id);

// ejecutar la consulta
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $property_data['title'] = $row['title'];
        $property_data['slug'] = $row['slug'];
        if ($row['image_path'] !== null && $row['image_id'] !== null) { // para evitar agregar imágenes nulas
            $property_data['images'][] = [
                'image_path' => $row['image_path'],
                'image_id' => $row['image_id']
            ];
        }
    }
}

// Si no hay título para la propiedad, asumimos que no se encontró la propiedad
if (empty($property_data['title'])) {
    header("Location: " . BASE_URL . "/admin/properties.php");
    $stmt->close();
    $mydb->close();
    exit;
}

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
            <h1>Administrar imágenes de <?php echo $property_data['title'] ?></h1>
            <a class="btn btn-secondary" href="<?php echo BASE_URL ?>/admin/edit-property.php?slug=<?php echo $property_data['slug'] ?>">Volver a la propiedad</a>
        </div>

        <form class="admin-form" action="./actions/upload_images.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="property_id" value="<?php echo $property_id ?>">
            <div class="images-input">
                <label>Selecciona las imágenes...</label>
                <input type="file" id="images" class="show" name="images[]" accept=".jpg, .jpeg, .png, .webp" multiple>
                <input id="submit-btn" class="btn btn-primary" type="submit" value="Subir imágenes">
            </div>
        </form>

        <?php
        if (isset($_SESSION['success'])) {
            echo '<p class="success">';
            echo $_SESSION['success'];
            echo '</p>';
            unset($_SESSION['success']);
        }
        ?>

        <?php if (!empty($property_data['images'])) { ?>
            <section class="images-grid">
                <?php
                foreach ($property_data['images'] as $image) {
                    include "./components/image_container.php";
                }
                ?>
            </section>
        <?php } else { ?>
            <div>
                Esta propiedad no posee imágenes...
            </div>
        <?php } ?>
    </main>
</body>

</html>