<?php
if(isset($_SESSION['user']))
{
    if(isset($_SESSION['carHistory']))
    {
        $tomorrow = date("Y-m-d", strtotime("+1 day"));

        //Zapytanie do bazy o wypozyczenia, serwisy i przeglad dla kalendarza
        $calendarstmt = $db->prepare("SELECT 'wypozyczenie' AS TYPE, idwypozyczenia AS id, w.datapoczatek, w.datakoniec AS datakoniec, k.nazwisko AS nazwisko, a.rejestracja AS rejestracja
        FROM wypozyczenia w
        INNER JOIN klienci k ON w.idklienta = k.idklienta
        INNER JOIN auta a ON w.idauta = a.idauto
        WHERE a.idauto = :idauto
        UNION ALL

        SELECT 'serwis' AS TYPE, idserwis AS id, s.datapoczatek, s.datakoniec AS datakoniec, NULL AS nazwisko, a.rejestracja AS rejestracja
        FROM serwis s
        INNER JOIN auta a ON s.idauta = a.idauto
        WHERE a.idauto = :idauto
        UNION ALL

        SELECT 'przeglad' AS TYPE, idprzegladu AS id, p.dataprzegladu AS datapoczatek, p.dataprzegladu AS datakoniec, NULL AS nazwisko, a.rejestracja AS rejestracja
        FROM przeglad p
        INNER JOIN auta a ON p.idauta = a.idauto
        WHERE a.idauto = :idauto");

        $calendarstmt->bindValue(':idauto', $_SESSION['carHistory'], PDO::PARAM_INT);
        $calendarstmt->execute();

        $events = $calendarstmt->fetchAll();

        $calendarEvents = [];
        foreach ($events as $event) {
            $title = 'Wypożyczenie ';
            if ($event['type'] == 'wypozyczenie') {
                $title .= ' (nr. '.$event['id'].')';
            }
            if ($event['type'] == 'serwis') {
                $title = 'Serwis (nr. '.$event['id'].')';
            }
            if ($event['type'] == 'przeglad') {
                $title = 'Przegląd (nr. '.$event['id'].')';
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


        $pstmt = $db->prepare('SELECT REJESTRACJA, MR.NAZWAMARKI AS MARKA, MD.NAZWAMODEL AS MODEL
                    FROM AUTA A
                    INNER JOIN MODEL MD ON A.IDMODEL=MD.IDMODEL
                    INNER JOIN MARKA MR ON MD.IDMARKA=MR.IDMARKA
                    WHERE A.IDAUTO=:idauto;');

        $pstmt->bindValue(':idauto', $_SESSION['carHistory'], PDO::PARAM_INT);
        $pstmt->execute();

        $auto = $pstmt->fetch();

        $rentstmt = $db->prepare('SELECT idwypozyczenia, datapoczatek, datakoniec, W.PRZEBIEGSTART, W.PRZEBIEGKONIEC, K.IMIE, K.NAZWISKO, K.TELEFON, SUMA, REALIZACJA, zaplacono
                    FROM wypozyczenia W
                    INNER JOIN AUTA A on A.idauto=W.idauta
                    INNER JOIN KLIENCI K ON W.IDKLIENTA=K.IDKLIENTA
                    WHERE W.IDAUTA=:idauto;');

        $rentstmt->bindValue(':idauto', $_SESSION['carHistory'], PDO::PARAM_INT);
        $rentstmt->execute();

        $rentResult = $rentstmt->fetchAll();

        $servicestmt = $db->prepare("SELECT IDSERWIS, P.IMIE AS IMIE, P.NAZWISKO AS NAZWISKO, DATAPOCZATEK, DATAKONIEC, NAZWASERWISU, OPIS, S.UWAGI, KOSZT
                    FROM SERWIS S
                    INNER JOIN AUTA A ON A.IDAUTO = S.IDAUTA
                    INNER JOIN PRACOWNICY P ON P.IDPRACOWNIKA=S.IDPRACOWNIKA
                    WHERE S.IDAUTA=:idauto;");
        
        $servicestmt->bindValue(':idauto', $_SESSION['carHistory'], PDO::PARAM_INT);
        $servicestmt->execute();

        $serviceResult = $servicestmt->fetchAll();


        $inspectstmt = $db->prepare('SELECT idprzegladu, dataprzegladu, datawaznosci, p.uwagi
                    FROM PRZEGLAD P
                    INNER JOIN AUTA A ON A.IDAUTO = P.IDAUTA
                    WHERE P.IDAUTA=:idauto;');

        $inspectstmt->bindValue(':idauto', $_SESSION['carHistory'], PDO::PARAM_INT);
        $inspectstmt->execute();

        $inspectResult = $inspectstmt->fetchAll();

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
}