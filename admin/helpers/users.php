<?php

function get_profile_picture($user) {
    if(isset($user["profile_picture"]) && $user["profile_picture"]) {
        return BASE_URL . $user["profile_picture"];
    } else {
        return BASE_URL . '/assets/img/avatar.webp';
    }
}


function get_last_login($user) {
    $date = $user["last_login"];

    if($date) {
        return 'Última vez el ' . format_date($date);
    } else {
        return 'Aún no se ha conectado...';
    }
}

?>