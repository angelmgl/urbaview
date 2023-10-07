<?php

// obtener la url del thumbnail de una propiedad, o sino el placeholder
function get_thumbnail($property)
{
    if (isset($property["thumbnail"]) && $property["thumbnail"]) {
        return BASE_URL . $property["thumbnail"];
    } else {
        return BASE_URL . '/assets/img/property.png';
    }
}

// generar el slug a partir del título de una propiedad
function generate_slug($title)
{
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

// agregar puntos separadores de centenas a precios de propiedades
function format_number($number)
{
    return number_format($number, 0, ',', '.');
}

// función que recibe la url de un video y devuelve el id necesario para el iframe de videos
function get_video_id($video_url)
{
    if (preg_match('/youtu\.be\/([a-zA-Z0-9_\-]+\?si=[a-zA-Z0-9_\-]+)/', $video_url, $matches)) {
        return $matches[1];
    }
    return false;
}

// función que recibe una propiedad y devuelve su precio 
function get_price($property)
{
    $has_price = $property["price_usd"] || $property["price_gs"];
    $is_usd = $property["price_usd"] > 0;

    return $has_price
        ? ($is_usd ? "USD " . format_number($property["price_usd"]) : "GS " . format_number($property["price_gs"]))
        : "No aplica";
}
