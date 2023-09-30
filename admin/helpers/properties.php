<?php 

function get_thumbnail($property) {
    if(isset($property["thumbnail"]) && $property["thumbnail"]) {
        return BASE_URL . $property["thumbnail"];
    } else {
        return BASE_URL . '/assets/img/property.png';
    }
}