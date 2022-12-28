<?php

$stmt = $db->query("SELECT idklienta, rodzajdokumentu, nrdokumentu, pesel, imie, nazwisko, telefon, email, KR.NAZWAKRAJ AS KRAJ, M.nazwamiasto AS MIASTO, ulica, nrdomu, nrmieszkania, kodpocztowy, uwagi
FROM KLIENCI K
INNER JOIN MIASTO M ON K.IDMIASTO = M.IDMIASTO
INNER JOIN KRAJ KR ON M.IDKRAJ = KR.IDKRAJ
WHERE idklienta={$_SESSION['clientDetails']};");

$result = $stmt->fetchAll();

if (array_key_exists('event', $_GET)) 
{
    if ($_GET['event'] == "edit") 
    {
        $_SESSION['clientEdit'] = $_SESSION['clientDetails'];
        unset($_SESSION['clientDetails']);
        redirect(url('clientEdit'));
    }

    if ($_GET['event'] == "back") 
    {
        unset($_SESSION['clientDetails']);
        redirect(url('clientList'));
    }
}
