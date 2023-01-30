<?php

$stmt = $db->query("SELECT IDWYPOZYCZENIA, W.IDAUTA, MR.NAZWAMARKI AS MARKA, MD.NAZWAMODEL AS MODEL, A.REJESTRACJA, W.IDKLIENTA ,K.IMIE, K.NAZWISKO, K.TELEFON, K.EMAIL,
                    P.IDPRACOWNIKA, P.IMIE AS PRACIMIE, P.NAZWISKO AS PRACNAZWISKO, P.LOGIN, DATAPOCZATEK, DATAKONIEC, PRZEBIEGSTART, PRZEBIEGKONIEC, SUMA, ZAPLACONO, W.UWAGI
                    FROM WYPOZYCZENIA W
                    INNER JOIN AUTA A ON A.IDAUTO = W.IDAUTA
                    INNER JOIN MODEL MD ON MD.IDMODEL = A.IDMODEL
                    INNER JOIN MARKA MR ON MR.IDMARKA = MD.IDMARKA
                    INNER JOIN KLIENCI K ON K.IDKLIENTA = W.IDKLIENTA
                    INNER JOIN PRACOWNICY P ON P.IDPRACOWNIKA = W.IDPRACOWNIKA
                    WHERE IDWYPOZYCZENIA={$_SESSION['rentDetails']};");

$row = $stmt -> fetch();

if($row['datakoniec'] == null || !($row['datakoniec']))
{
    $przejechano = '-';
    $dni = (strtotime(date('Y-m-d')) - strtotime($row['datapoczatek'])) / 60 / 60 / 24;
}
else
{
    $przejechano = $row['przebiegkoniec'] - $row['przebiegstart'];
    $dni = (strtotime($row['datakoniec']) - strtotime($row['datapoczatek'])) / 60 / 60 / 24;
}

if (array_key_exists('event', $_GET)) 
{

    if ($_GET['event'] == "back") 
    {
        unset($_SESSION['rentDetails']);
        redirect(url('rentList'));
    }

    if($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik")
    {
        if ($_GET['event'] == "car") 
        {
            $_SESSION['carDetails']=$row['idauta'];
            //unset($_SESSION['rentDetails']);
            redirect(url('carDetails'));
        }

        if ($_GET['event'] == "client") 
        {
            $_SESSION['clientDetails']=$row['idklienta'];
            //unset($_SESSION['rentDetails']);
            redirect(url('clientDetails'));
        }

        if ($_GET['event'] == "user") 
        {
            $_SESSION['userDetails']=$row['idpracownika'];
            //unset($_SESSION['rentDetails']);
            redirect(url('userDetails'));
        }
    }

}

