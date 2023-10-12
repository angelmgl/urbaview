<?php 

require '../config/config.php';

// Selecciona las propiedades que vencieron antes de hoy
$sql = "
    SELECT properties.*
    FROM properties 
    WHERE properties.status = 'publicado' 
    AND properties.expiration_date < CURDATE()";
$result = $mydb->query($sql);

// Si hay propiedades que vencieron
if ($result->num_rows > 0) {
    // Actualizar el estado de las propiedades que vencieron a "borrador"
    $update_sql = "
        UPDATE properties 
        SET status = 'borrador' 
        WHERE properties.status = 'publicado' 
        AND properties.expiration_date < CURDATE()";
    $update_result = $mydb->query($update_sql);

    if ($update_result) {
        echo "Se actualizaron " . $mydb->affected_rows . " propiedades a 'borrador'.";
    } else {
        echo "Hubo un error al actualizar las propiedades: " . $mydb->error;
    }
} else {
    echo "No hay propiedades que hayan vencido.";
}

?>