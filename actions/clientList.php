<?php

$stmt = $db->query('SELECT idklienta, rodzajdokumentu, nrdokumentu, pesel, imie, nazwisko, telefon, email, KR.NAZWAKRAJ AS KRAJ, M.nazwamiasto AS MIASTO
            FROM KLIENCI K
            INNER JOIN MIASTO M ON K.IDMIASTO = M.IDMIASTO
            INNER JOIN KRAJ KR ON M.IDKRAJ = KR.IDKRAJ;');

$result = $stmt->fetchAll();



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
