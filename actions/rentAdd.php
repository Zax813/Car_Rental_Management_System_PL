<?php

function getCountryData($db) {
    
    // Pobierz dostępne numery rejestracyjne z bazy danych
    $stmt = $db->prepare("SELECT nazwakraj AS kraj FROM kraj;");
    $stmt->execute();

    $kraje = $stmt->fetchAll();

    $kraje_values = array_map(function($kraj) {
        return $kraj['kraj'];
    }, $kraje);
        
    // Wypisz numery rejestracyjne jako odpowiedź w formacie JSON
    echo json_encode($kraje_values);
}

function getCityData($db, $kraj) {

    // Pobierz dostępne numery rejestracyjne z bazy danych

    if ($kraj == null || $kraj == '') {
        $stmt = $db->prepare("SELECT nazwamiasto AS miasto FROM miasto;");
    } else {

        $check = $db->prepare("SELECT idkraj FROM kraj 
        WHERE nazwakraj LIKE :kraj;");
        $check->bindValue(':kraj', $kraj);
        $check->execute();
        $kraj = $check->fetch();

        $stmt = $db->prepare("SELECT nazwamiasto AS miasto FROM miasto 
        WHERE idkraj LIKE :idkraj;");
        $stmt->bindValue(':idkraj', $kraj['idkraj']);
    }
    $stmt->execute();

    $miasta = $stmt->fetchAll();

    $miasta_values = array_map(function($miasto) {
        return $miasto['miasto'];
    }, $miasta);
        
    // Wypisz numery rejestracyjne jako odpowiedź w formacie JSON
    echo json_encode($miasta_values);
}


if((array_key_exists('event', $_GET)))
{
    if($_GET['event']=='list')
    {
        redirect(url('home'));
    }
}

if (isset($_SESSION['rentAdd'])) {

    $check = $db ->query("SELECT IDAUTO, REJESTRACJA, MR.NAZWAMARKI AS MARKA, MD.NAZWAMODEL AS MODEL, PRZEBIEG
                            FROM AUTA A
                            INNER JOIN MODEL MD ON A.IDMODEL=MD.IDMODEL
                            INNER JOIN MARKA MR ON MD.IDMARKA=MR.IDMARKA
                            WHERE IDAUTO = {$_SESSION['rentAdd']};");

    $auto = $check -> fetch();     

    $row['idauto'] = $auto['idauto'];
    $row['samochod'] = $auto['marka'].' '.$auto['model'];
    $row['numer'] = $auto['rejestracja'];
    $row['przebiegstart'] = $auto['przebieg'];     

    // Pobierz aktualną datę w formacie YYYY-MM-DD
    $today = date("Y-m-d");

    // Dodaj dzień do przodu
    $nextDay = date("Y-m-d", strtotime("+1 day"));

    $fields['datapoczatek'] = array_key_exists('datapoczatek', $_POST) ? $_POST['datapoczatek'] : $today;
    $fields['datakoniec'] = array_key_exists('datakoniec', $_POST) ? $_POST['datakoniec'] : $nextDay;

    $fields['samochod'] = array_key_exists('samochod', $_POST) ? $_POST['samochod'] : $row['samochod'];
    $fields['numer'] = array_key_exists('numer', $_POST) ? $_POST['numer'] : $row['numer'];
    $fields['przebiegstart'] = array_key_exists('przebiegstart', $_POST) ? $_POST['przebiegstart'] : $row['przebiegstart'];
    
    $fields['imie'] = array_key_exists('imie', $_POST) ? $_POST['imie'] : '';
    $fields['nazwisko'] = array_key_exists('nazwisko', $_POST) ? $_POST['nazwisko'] : '';
    $fields['pesel'] = array_key_exists('pesel', $_POST) ? $_POST['pesel'] : '';
    $fields['rodzajdokumentu'] = array_key_exists('rodzajdokumentu', $_POST) ? $_POST['rodzajdokumentu'] : 'dowód osobisty';
    $fields['nrdokumentu'] = array_key_exists('nrdokumentu', $_POST) ? $_POST['nrdokumentu'] : '';
    $fields['telefon'] = array_key_exists('telefon', $_POST) ? $_POST['telefon'] : '';
    $fields['email'] = array_key_exists('email', $_POST) ? $_POST['email'] : '';
    $fields['kraj'] = array_key_exists('kraj', $_POST) ? $_POST['kraj'] : '';
    $fields['miasto'] = array_key_exists('miasto', $_POST) ? $_POST['miasto'] : '';
    $fields['ulica'] = array_key_exists('ulica', $_POST) ? $_POST['ulica'] : '';
    $fields['nrdomu'] = array_key_exists('nrdomu', $_POST) ? $_POST['nrdomu'] : '';
    $fields['nrmieszkania'] = array_key_exists('nrmieszkania', $_POST) ? $_POST['nrmieszkania'] : '';
    $fields['kodpocztowy'] = array_key_exists('kodpocztowy', $_POST) ? $_POST['kodpocztowy'] : '';

    $fields['uwagi'] = array_key_exists('uwagi', $_POST) ? $_POST['uwagi'] : '';

    $errors = array();
    $info = "";

    if(isset($_POST["findClient"]))
    {
        $check = $db -> prepare("SELECT idklienta, rodzajdokumentu, nrdokumentu, pesel, imie, nazwisko, telefon, email, KR.NAZWAKRAJ AS KRAJ, M.nazwamiasto AS MIASTO, ulica, nrdomu, nrmieszkania, kodpocztowy, uwagi
                                FROM KLIENCI K
                                INNER JOIN MIASTO M ON K.IDMIASTO = M.IDMIASTO
                                INNER JOIN KRAJ KR ON M.IDKRAJ = KR.IDKRAJ
                                WHERE telefon = :telefon;");

        $check -> bindValue(':telefon', $fields['telefon']);
        $check -> execute();
        $row = $check -> fetch();

        if($row)
        {
            $fields['imie'] = $row['imie'];
            $fields['nazwisko'] = $row['nazwisko'];
            $fields['pesel'] = $row['pesel'];
            $fields['rodzajdokumentu'] = $row['rodzajdokumentu'];
            $fields['nrdokumentu'] = $row['nrdokumentu'];
            $fields['telefon'] = $row['telefon'];
            $fields['kraj'] = $row['kraj'];
            $fields['miasto'] = $row['miasto'];
            $fields['ulica'] = $row['ulica'];
            $fields['nrdomu'] = $row['nrdomu'];
            $fields['nrmieszkania'] = $row['nrmieszkania'];
            $fields['kodpocztowy'] = $row['kodpocztowy'];

        }
    }


    if (isset($_POST["acceptAdd"])) {

        if (empty($fields['imie'])) {
            $errors['imie'] = 'Pole jest wymagane.';
        }

        if (empty($fields['nazwisko'])) {
            $errors['nazwisko'] = 'Pole jest wymagane.';
        }

        if (empty($fields['rodzajdokumentu'])) {
            $errors['rodzajdokumentu'] = 'Pole jest wymagane.';
        }

        if (empty($fields['nrdokumentu'])) {
            $errors['nrdokumentu'] = 'Pole jest wymagane.';
        }

        if (empty($fields['telefon'])) {
            $errors['telefon'] = 'Pole jest wymagane.';
        }

        if (empty($fields['kraj'])) {
            $errors['kraj'] = 'Pole jest wymagane.';
        }

        if (empty($fields['miasto'])) {
            $errors['miasto'] = 'Pole jest wymagane.';
        }

        if (empty($fields['ulica'])) {
            $errors['ulica'] = 'Pole jest wymagane.';
        }

        if (empty($fields['nrdomu'])) {
            $errors['nrdomu'] = 'Pole jest wymagane.';
        }

        if (empty($fields['kodpocztowy'])) {
            $errors['kodpocztowy'] = 'Pole jest wymagane.';
        }

        if (empty($fields['uwagi'])) {
            $_POST['uwagi'] = 'Brak';
        }

        if (empty($fields['przebiegstart'])) {
            $errors['przebiegstart'] = 'Pole jest wymagane.';
        }

        if (count($errors) == 0) {

            try {
                $db->beginTransaction();

                $pstmt = $db->prepare("SELECT IDAUTO FROM AUTA WHERE REJESTRACJA=:numer;");
                $pstmt->bindValue(':numer', $_POST['numer']);
                $pstmt->execute();
                $idauta = $pstmt->fetchColumn();

                if (!$idauta) {
                    $errors['all'] = "Błąd: Nie znaleziono samochodu o podanej rejestracji w bazie";
                } else {
                    $ustmt = $db->prepare("INSERT INTO serwis( idauta, idpracownika, datapoczatek, datakoniec, nazwaserwisu, opis, uwagi, koszt)
                                        VALUES ( :idauta, :idpracownik, :datapoczatek, :datakoniec, :nazwaserwisu, :opis, :uwagi, :koszt);");

                    $ustmt->bindValue(':idauta', $idauta, PDO::PARAM_INT);
                    $ustmt->bindValue(':idpracownik', $_SESSION['uid'], PDO::PARAM_INT);
                    $ustmt->bindValue(':datapoczatek', $_POST['datapoczatek']);
                    $ustmt->bindValue(':datakoniec', $_POST['datakoniec']);
                    $ustmt->bindValue(':nazwaserwisu', $_POST['nazwaserwis']);
                    $ustmt->bindValue(':opis', $_POST['opis']);
                    $ustmt->bindValue(':uwagi', $_POST['uwagi']);
                    $ustmt->bindValue(':koszt', $_POST['koszt']);
                    $ustmt->execute();
                }

                $db->commit();
            } catch (PDOException $e) {
                $db->rollBack();
                $errors['all'] = "Błąd: " . $e->getMessage();
                $info = "";
            }

            if (count($errors) == 0) {
                $info = "Przegląd dodany pomyślnie";
            }
        }
    }
}