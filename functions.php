<?php

function include_template($name, $data)
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}


function format_price($arg)
{
    $price = ceil($arg);
    $result = number_format($price, 0, '', ' ');
    $result .= ' <b class="rub">р</b>';

    return $result;
}

function format_price_string($argument)
{
    $price = (string)ceil($argument);
    $result = $price;

    if ($price >= 1000) {
        $last_numbers = substr($price, -3);
        $count_diff = strlen($price) - strlen($last_numbers);
        $first_numbers = substr($price, 0, $count_diff);

        $result = $first_numbers . ' ' . $last_numbers;
    }

    $result .= ' <b class="rub">р</b>';
    return $result;
}

// Time end
function lot_time_end()
{
    $current_date = date_create('now');
    $new_day = date_create('tomorrow 00:00:00');

    $diff = date_diff($current_date, $new_day);

    $format = date_interval_format($diff, '%H:%I');

    return $format;
}
