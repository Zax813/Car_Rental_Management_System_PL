<?php

$stmt = $db->query('SELECT IDSERWIS, MR.NAZWAMARKI AS MARKA, MD.NAZWAMODEL AS MODEL, A.REJESTRACJA, DATAPOCZATEK, DATAKONIEC, NAZWASERWISU
                    FROM SERWIS S
                    INNER JOIN AUTA A ON A.IDAUTO = S.IDAUTA
                    INNER JOIN MODEL MD ON A.IDMODEL=MD.IDMODEL
                    INNER JOIN MARKA MR ON MD.IDMARKA=MR.IDMARKA
                    WHERE AKTYWNY = TRUE;');

$result = $stmt->fetchAll();


if ((array_key_exists('event', $_GET))) 
{
    if ($_GET['event'] == "edit") 
    {
        $_SESSION['serviceEdit'] = $_GET['value'];
        redirect(url('carServiceEdit'));
    }

    if ($_GET['event'] == "details") 
    {
        $_SESSION['serviceDetails'] = $_GET['value'];
        redirect(url('carServiceDetails'));
    }
}
