<?php

$stmt = $db->prepare('SELECT idprzegladu, MR.nazwamarki AS MARKA, MD.nazwamodel AS MODEL, A.REJESTRACJA, dataprzegladu, datawaznosci, p.uwagi
                    FROM PRZEGLAD P
                    INNER JOIN AUTA A ON A.IDAUTO = P.IDAUTA
                    INNER JOIN MODEL MD ON A.IDMODEL=MD.IDMODEL
                    INNER JOIN MARKA MR ON MD.IDMARKA=MR.IDMARKA
                    WHERE idprzegladu=:idprzegladu;');

$stmt->bindValue(':idprzegladu', $_SESSION['carInspectionDetails'], PDO::PARAM_INT);
$stmt->execute();

$result = $stmt->fetchAll();

if(array_key_exists('event', $_GET))
{
    if ($_GET['event'] == "back") 
    {
        unset($_SESSION['carInspectionDetails']);
        redirect(url('carInspectionList'));
    }
}