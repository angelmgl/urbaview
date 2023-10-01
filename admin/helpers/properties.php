<?php 

function get_thumbnail($property) {
    if(isset($property["thumbnail"]) && $property["thumbnail"]) {
        return BASE_URL . $property["thumbnail"];
    } else {
        return BASE_URL . '/assets/img/property.png';
    }
}

function generate_slug($title) {
    // Convertir a minúsculas y quitar acentos
    $slug = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $title));

    // Reemplazar caracteres especiales y espacios por guiones
    $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
    
    // Eliminar guiones múltiples y guiones al principio y final
    $slug = trim($slug, '-');
    
    // Añadir sufijo aleatorio
    $suffix = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 6);
    $slug .= '-' . $suffix;

    return $slug;
}