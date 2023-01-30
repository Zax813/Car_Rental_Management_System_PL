<?php

if(isset($_SESSION['user']))
{
    $tomorrow = date("Y-m-d", strtotime("+1 day"));

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

    //Zapytanie do bazy o wypozyczenia, serwisy i przeglad dla kalendarza
    $stmt = $db->prepare("SELECT 'wypozyczenie' AS TYPE, w.datapoczatek, w.datakoniec, k.nazwisko AS nazwisko, a.rejestracja AS rejestracja
    FROM wypozyczenia w
    INNER JOIN klienci k ON w.idklienta = k.idklienta
    INNER JOIN auta a ON w.idauta = a.idauto
    WHERE a.idauto = :idauto
    UNION ALL

    SELECT 'serwis' AS TYPE, s.datapoczatek, s.datakoniec, NULL AS nazwisko, a.rejestracja AS rejestracja
    FROM serwis s
    INNER JOIN auta a ON s.idauta = a.idauto
    WHERE a.idauto = :idauto
    UNION ALL

    SELECT 'przeglad' AS TYPE, p.dataprzegladu AS datapoczatek, p.dataprzegladu AS datakoniec, NULL AS nazwisko, a.rejestracja AS rejestracja
    FROM przeglad p
    INNER JOIN auta a ON p.idauta = a.idauto
    WHERE a.idauto = :idauto");

    $stmt->bindValue(':idauto', $_SESSION['carDetails'], PDO::PARAM_INT);
    $stmt->execute();

    $events = $stmt->fetchAll();

    $calendarEvents = [];
    foreach ($events as $event) {
        $title = 'Wypożyczenie ';
        if ($event['type'] == 'wypozyczenie') {
            $title .= ' (' . $event['nazwisko'] . ')';
        }
        if ($event['type'] == 'serwis') {
            $title = ' ( Serwis )';
        }
        if ($event['type'] == 'przeglad') {
            $title = ' ( Przegląd )';
        }
        if($event['datakoniec'] == null || $event['datakoniec'] == '0000-00-00' || $event['datakoniec'] == '')
        {
            $event['datakoniec'] = $tomorrow;
        }

        $calendarEvents[] = [
            'start' => $event['datapoczatek'],
            'end' => $event['datakoniec'],
            'title' => $title,
        ];
    }


}
else
{
    redirect(url('login'));
}

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
