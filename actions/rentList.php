<?php

$stmt = $db->query('SELECT idwypozyczenia, MR.NAZWAMARKI AS MARKA, MD.NAZWAMODEL AS MODEL, A.rejestracja, K.IMIE, K.NAZWISKO, K.TELEFON, datapoczatek, datakoniec, przebiegstart, przebiegkoniec, suma, zaplacono, W.uwagi
                    FROM wypozyczenia w
                    INNER JOIN AUTA A ON A.idauto = w.idauta
                    INNER JOIN model MD ON MD.idmodel = A.idmodel
                    INNER JOIN MARKA MR ON MR.idmarka = MD.idmarka
                    INNER JOIN klienci K ON K.idklienta = w.idklienta;');

$result = $stmt->fetchAll();



if ((array_key_exists('event', $_GET))) {

    if ($_GET['event'] == "edit") {
        $_SESSION['rentEdit'] = $_GET['value'];
        redirect(url('rentEdit'));
    }

    if ($_GET['event'] == "details") {
        $_SESSION['rentDetails'] = $_GET['value'];
        redirect(url('rentDetails'));
    }

    if ($_GET['event'] == "final") {
        $_SESSION['rentFinal'] = $_GET['value'];
        redirect(url('rentFinal'));
    }

    if ($_GET['event'] == "cancel") {
        $_SESSION['rentCancel'] = $_GET['value'];
        redirect(url('rentCancel'));
    }

}
