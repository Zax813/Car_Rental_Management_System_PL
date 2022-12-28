<?php

    $stmt = $db->query("SELECT idpracownika, login, imie, nazwisko, telefon, email, zatrudniony, uprawnienia 
                        FROM pracownicy 
                        WHERE idpracownika={$_SESSION['userDetails']};");

    $row = $stmt->fetch();

    if((array_key_exists('event', $_GET)))
    {
        if($_GET['event']=="add")
        {
            $_SESSION['userEdit']=$_SESSION['userDetails'];
            unset($_SESSION['userDetails']);
            redirect(url('userEdit'));
        }

        if($_GET['event']=="back")
        {
            unset($_SESSION['userDetails']);
            redirect(url('userList'));
        }
    }

?>