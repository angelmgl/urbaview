<?php 

function format_date($date) {
    $d = new DateTime($date);

    $formatter = new IntlDateFormatter(
        'es_ES',
        IntlDateFormatter::LONG,
        IntlDateFormatter::SHORT,
        date_default_timezone_get(),
        IntlDateFormatter::GREGORIAN,
        'd \'de\' MMMM \'de\' Y \'a las\' HH:mm \'hs\''
    );

    return $formatter->format($d);
}

function get_last_login($date) {
    if($date) {
        return 'Última vez el ' . format_date($date);
    } else {
        return 'Aún no se ha conectado...';
    }
}
