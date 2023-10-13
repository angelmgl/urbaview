<?php

require '../config/config.php';
require '../helpers/mails.php';

$sql = "
    SELECT properties.*, users.full_name, users.email, DATEDIFF(properties.expiration_date, CURDATE()) as days_remaining
    FROM properties 
    INNER JOIN users ON properties.user_id = users.id 
    WHERE properties.status = 'publicado' 
    AND properties.expiration_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 5 DAY)";
$result = $mydb->query($sql);

// Carga la plantilla
$template = file_get_contents('../helpers/email_template.html');

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subject = "Renovación de Tour Virtual 360 ⚠️";
        $user_email = $row['email'];
        $user_name = $row['full_name'];
        $days_remaining = $row['days_remaining'];
        $formatted_expiration_date = date("d/m/Y", strtotime($row['expiration_date']));  // Formatea la fecha a un formato legible, p.ej. 25/12/2023

        $first_message = "El siguiente Tour Virtual 360 está por vencer en {$days_remaining} días:";
        $property = "{$row['title']}.";
        $second_message = "Contactá con nosotros para renovar tu suscripción antes que deje de estar visible para todos!";
        $button_url = "https://wa.me/+595981452254";
        $button_text = "Contactá al administrador";
        $recommendation = "Si omites este mail, el Tour dejará de estar visible el";
        $expiration = $formatted_expiration_date;

        // usa tu función de envío de correo aquí, por ejemplo:
        send_notification(
            $template, 
            $subject, 
            $user_email, 
            $user_name, 
            $first_message, 
            $property, 
            $second_message,
            $button_url, 
            $button_text,
            $recommendation,
            $expiration
        );
    }
} else {
    // Aquí puedes hacer algo si no hay propiedades que estén próximas a vencer.
    // Por ejemplo, puedes registrar un mensaje en un archivo de log, enviar un aviso, etc.
    echo "No hay propiedades próximas a vencer en los próximos 7 días.";
}

?>