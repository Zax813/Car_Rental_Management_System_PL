<?php

    $stmt = $db->query('SELECT idpracownika,login,imie,nazwisko,uprawnienia,telefon,email,zatrudniony FROM pracownicy ORDER BY idpracownika');


    if((array_key_exists('event', $_GET)))
    {
        if($_GET['event']=="add")
        {
            $_SESSION['edit']=$_GET['value'];
            redirect(url('editUser'));
        }
    }

?>