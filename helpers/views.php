<?php 

function register_view($id, $type, $mydb) {
    $cookie_name = "visited_{$type}_" . $id;
    $table_name = "{$type}_views";
    $column_name = $type . "_id";

    // Verificar si la cookie ya está establecida
    if (!isset($_COOKIE[$cookie_name])) {
        // Insertar una nueva entrada en la tabla correspondiente.
        $stmt = $mydb->prepare("INSERT INTO {$table_name} ({$column_name}) VALUES (?)");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Establecer la cookie para evitar que se cuente otra visita durante la duración de la cookie.
        // La cookie expira después de 7 días.
        setcookie($cookie_name, 'true', time() + (86400 * 7), "/");
    }
}

function get_views($id, $type, $mydb) {
    $table_name = "{$type}_views";
    $column_name = $type . "_id";

    // Preparar la consulta SQL para obtener el total de visitas
    $stmt = $mydb->prepare("SELECT COUNT(*) as total_views FROM {$table_name} WHERE {$column_name} = ?");
    $stmt->bind_param("i", $id);
    
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
