<?php

//Funkcja sprawdzająca czy numer telefonu zawiera tylko cyfry i znak '+'
function validtel($tel) 
{
    $reg = '/^[0-9\+]{8,13}$/';
    return preg_match($reg, $tel);
}

function in_array_r($needle, $stack, $strict = false)
{
    foreach ($stack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }
    return false;
}

function m_array_str($array, $col)
{
    $t = '';
    foreach ($array as $val) 
    {
        if (is_array($val)) 
        {
            $t .= '\'' . $val[$col] . '\',';
        }
    }
    if (substr($t, -1) == ',') 
    {
        $t = substr_replace($t, '', -1);
    }
    return $t;
}


//Funkcja sprawdzająca czy w tabeli znajduje się przynajmniej jeden pracownik z uprawnieniami administratora
function is_admins($db)
{
    $stmt = $db->query("SELECT idpracownika FROM pracownicy WHERE stanowisko='admin'");
    $data = $stmt->fetchAll();

    $count = 0;
    foreach ($data as $data => $inner_array) 
    {
        $count = $count + 1;
    }

    if ($count > 1) 
    {
        return true;
    } 
    else 
    {
        return false;
    }
}

//Funkcja do wypisywania logów z poziomu php za pomocą JavaScript
function console_log($output, $with_script_tags = true)
{
    $js_code = 'console.log('.json_encode($output, JSON_HEX_TAG).');';
    if($with_script_tags){
        $js_code = '<script>'.$js_code.'</script>';
    }
    echo $js_code;
}