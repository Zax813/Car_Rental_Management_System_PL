<?php

    $stmt = $db->query("SELECT idpracownika, login, imie, nazwisko, telefon, email, zatrudniony, uprawnienia FROM pracownicy WHERE idpracownika={$_SESSION['details']};");

    $result = $stmt->fetchAll();

    if((array_key_exists('event', $_GET)))
    {
        if($_GET['event']=="add")
        {
            $_SESSION['edit']=$_SESSION['details'];
            unset($_SESSION['details']);
            redirect(url('userEdit'));
        }

        if($_GET['event']=="back")
        {
            unset($_SESSION['details']);
            redirect(url('userList'));
        }
    }

?>