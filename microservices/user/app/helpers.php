<?php

if(!function_exists('generate_random_digits_with_specefic_length'))
{
    function generate_random_digits_with_specefic_length(int $length)
    {
        return substr(number_format(time() * rand(), 0, '', ''), 0, $length);
    }
}

function validate_national_id($national_id)
{
    $national_id = strval($national_id);

    if(strlen($national_id) != 10) return false;

    $check = intval($national_id[9]);

    $sum = 0;

    for( $i = 0; $i < 9; $i++ )
    {
        $sum += intval($national_id[ $i ]) * (10 - $i);
    }

    $remainder = $sum % 11;

    if(
        ($remainder < 2 && $check == $remainder) or
        ($remainder >= 2 && $check == 11 - $remainder)
    ) return true;

    return false;
}
