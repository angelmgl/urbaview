<?php 

function register_property_view($property_id, $mydb) {
    $cookie_name = "visited_property_" . $property_id;

    // Verificar si la cookie ya está establecida
    if (!isset($_COOKIE[$cookie_name])) {
        // Insertar una nueva entrada en la tabla `property_views`.
        $stmt = $mydb->prepare("INSERT INTO property_views (property_id) VALUES (?)");
        $stmt->bind_param("i", $property_id);
        $stmt->execute();
        $stmt->close();

        // Establecer la cookie para evitar que se cuente otra visita durante la duración de la cookie.
        // La cookie expira después de 7 días.
        setcookie($cookie_name, 'true', time() + (86400 * 7), "/");
        echo("cookie $cookie_name seteada");
    } else {
        echo("cookie $cookie_name ya existe");
    }
}

function get_property_views($property_id, $mydb) {
    // Preparar la consulta SQL para obtener el total de visitas de la propiedad
    $stmt = $mydb->prepare("SELECT COUNT(*) as total_views FROM property_views WHERE property_id = ?");
    $stmt->bind_param("i", $property_id);
    
    // Ejecutar la consulta
    $stmt->execute();
    
    // Obtener el resultado
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Cerrar el statement
    $stmt->close();
    
    // Retornar el total de vistas
    return $row['total_views'];
}