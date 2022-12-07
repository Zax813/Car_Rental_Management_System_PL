<?php

    $stmt = $db->query('SELECT idpracownika,login,imie,nazwisko,uprawnienia,zatrudniony FROM pracownicy ORDER BY idpracownika;');

    if((array_key_exists('event', $_GET)))
    {
        if($_GET['event']=="add")
        {
            $_SESSION['edit']=$_GET['value'];
            redirect(url('userEdit'));
        }

        if($_GET['event']=="details")
        {
            $_SESSION['details']=$_GET['value'];
            redirect(url('userDetails'));
        }
    }

?>