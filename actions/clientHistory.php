<?php

if(isset($_SESSION['clientHistory']))
{
    $pstmt = $db->prepare('SELECT imie, nazwisko
                FROM klienci
                WHERE idklienta=:idklienta;');

    $pstmt->bindValue(':idklienta', $_SESSION['clientHistory'], PDO::PARAM_INT);
    $pstmt->execute();
    $klient = $pstmt->fetch();

    $stmt = $db->prepare('SELECT idwypozyczenia, datapoczatek, datakoniec, MR.NAZWAMARKI AS MARKA, MD.NAZWAMODEL AS MODEL, A.REJESTRACJA, PRZEBIEGSTART, PRZEBIEGKONIEC, SUMA, REALIZACJA, zaplacono
                FROM wypozyczenia W
                INNER JOIN AUTA A on A.idauto=W.idauta
                INNER JOIN MODEL MD ON A.IDMODEL=MD.IDMODEL
                INNER JOIN MARKA MR ON MD.IDMARKA=MR.IDMARKA
                WHERE W.IDKLIENTA=:idklienta;');

    $stmt->bindValue(':idklienta', $_SESSION['clientHistory'], PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetchAll();
}

if ((array_key_exists('event', $_GET))) {
    
    if ($_GET['event'] == "edit") {
        $_SESSION['clientEdit'] = $_GET['value'];
        redirect(url('clientEdit'));
    }

    if ($_GET['event'] == "details") {
        $_SESSION['clientDetails'] = $_GET['value'];
        redirect(url('clientDetails'));
    }
}
