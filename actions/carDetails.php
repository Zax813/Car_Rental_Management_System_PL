<?php

$stmt = $db->prepare("SELECT IDAUTO, VIN, REJESTRACJA, Z.SCIEZKA AS ZDJECIE, Z.TYTUL AS TYTUL, S.NAZWASEGMENT AS SEGMENT, MR.NAZWAMARKI AS MARKA, MD.NAZWAMODEL AS MODEL, P.NAZWAPALIWO AS PALIWO, MOCKW, SKRZYNIA, LICZBAMIEJSC, ROK, AKTYWNY, SPRAWNY, DOSTEPNY, PRZEBIEG, CENADOBA, CENAKM, UWAGI
    FROM AUTA A
    INNER JOIN MODEL MD ON A.IDMODEL=MD.IDMODEL
    INNER JOIN MARKA MR ON MD.IDMARKA=MR.IDMARKA
    INNER JOIN SEGMENT S ON A.IDSEGMENT=S.IDSEGMENT
    INNER JOIN PALIWO P ON A.IDPALIWO=P.IDPALIWO
    LEFT JOIN ZDJECIA Z ON A.IDZDJECIE=Z.IDZDJECIE
    WHERE IDAUTO=:idauto;");

$stmt -> bindValue(':idauto', $_SESSION['carDetails']);
$stmt -> execute();

$row = $stmt->fetch();

$row['mockm'] = round($row['mockw'] * 1.36);

if (array_key_exists('event', $_GET)) 
{
    if ($_GET['event'] == "edit") 
    {
        $_SESSION['carEdit'] = $_SESSION['carDetails'];
        unset($_SESSION['carDetails']);
        redirect(url('carEdit'));
    }

    if ($_GET['event'] == "back") 
    {
        unset($_SESSION['carDetails']);
        redirect(url('home'));
    }
}
