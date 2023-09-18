<?php


// función para recuperar el valor por defecto de un input, cuando falla un form, a través de la key 
function get_form_data($key) {
    return isset($_SESSION['form_data'][$key]) ? $_SESSION['form_data'][$key] : '';
}

// función para establecer un mensaje de error y guardar los datos enviador de una solicitud fallida
function handleFormError($errorMessage, $formData, $return_url) {
    $_SESSION['error'] = $errorMessage;
    $_SESSION['form_data'] = $formData;
    header("Location: " . BASE_URL . $return_url);
    exit;
}

?>