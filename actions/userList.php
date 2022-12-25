<?php
$fields['search'] = array_key_exists('search', $_POST) ? $_POST['search'] : '';


$stmt = $db->query('SELECT idpracownika,login,imie,nazwisko,uprawnienia,zatrudniony FROM pracownicy ORDER BY zatrudniony DESC, idpracownika;');
$result = $stmt->fetchAll();



if ((array_key_exists('event', $_GET))) 
{
    if ($_GET['event'] == "add") 
    {
        $_SESSION['edit'] = $_GET['value'];
        redirect(url('userEdit'));
    }

    if ($_GET['event'] == "details") 
    {
        $_SESSION['details'] = $_GET['value'];
        redirect(url('userDetails'));
    }
}
