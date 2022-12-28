<?php

$stmt = $db->query("SELECT IDSERWIS, MR.NAZWAMARKI AS MARKA, MD.NAZWAMODEL AS MODEL, A.REJESTRACJA, P.IMIE AS IMIE, P.NAZWISKO AS NAZWISKO, DATAPOCZATEK, DATAKONIEC, NAZWASERWISU, OPIS, S.UWAGI, KOSZT
                    FROM SERWIS S
                    INNER JOIN AUTA A ON A.IDAUTO = S.IDAUTA
                    INNER JOIN MODEL MD ON A.IDMODEL=MD.IDMODEL
                    INNER JOIN MARKA MR ON MD.IDMARKA=MR.IDMARKA
                    INNER JOIN PRACOWNICY P ON P.IDPRACOWNIKA=S.IDPRACOWNIKA
                    WHERE IDSERWIS={$_SESSION['serviceDetails']};");

$result = $stmt -> fetchAll();

if (array_key_exists('event', $_GET)) 
{
    if ($_GET['event'] == "edit") 
    {
        $_SESSION['serviceEdit']= $_SESSION['serviceDetails'];
        unset($_SESSION['serviceDetails']);
        redirect(url('carServiceEdit'));
    }

    if ($_GET['event'] == "back") 
    {
        unset($_SESSION['serviceDetails']);
        redirect(url('carServiceList'));
    }
}
