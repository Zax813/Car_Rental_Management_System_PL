<?php

$stmt = $db->query('SELECT idprzegladu, MR.nazwamarki AS MARKA, MD.nazwamodel AS MODEL, A.REJESTRACJA, dataprzegladu, datawaznosci, p.uwagi
                    FROM PRZEGLAD P
                    INNER JOIN AUTA A ON A.IDAUTO = P.IDAUTA
                    INNER JOIN MODEL MD ON A.IDMODEL=MD.IDMODEL
                    INNER JOIN MARKA MR ON MD.IDMARKA=MR.IDMARKA;');

$result = $stmt->fetchAll();


/*
if ((array_key_exists('event', $_GET))) {
    if ($_GET['event'] == "edit") {
        $_SESSION['edit'] = $_GET['value'];
        redirect(url('clientEdit'));
    }

    if ($_GET['event'] == "details") {
        $_SESSION['details'] = $_GET['value'];
        redirect(url('clientDetails'));
    }
}
*/