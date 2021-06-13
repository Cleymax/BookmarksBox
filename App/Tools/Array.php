<?php


function get_array_index($key, array $array): int
{
    $i = -1;
    foreach ($array as $k => $v) {
        if ($k == $key) {
            break;
        }
        if ($i < 0) {
            $i = 0;
        }
        $i++;
    }

    return $i;
}
