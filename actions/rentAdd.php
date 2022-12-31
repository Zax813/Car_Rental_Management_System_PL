<?php

function getCountryData($db) {
    
    // Pobierz dostępne kraje z bazy danych
    $stmt = $db->prepare("SELECT nazwakraj AS kraj FROM kraj;");
    $stmt->execute();

    $kraje = $stmt->fetchAll();

    $kraje_values = array_map(function($kraj) {
        return $kraj['kraj'];
    }, $kraje);
        
    // Wypisz kraje jako odpowiedź w formacie JSON
    echo json_encode($kraje_values);
}

function getCityData($db) {

        $stmt = $db->prepare("SELECT k.nazwakraj AS kraj, m.nazwamiasto AS miasto FROM miasto  m
                                INNER JOIN kraj k ON k.idkraj = m.idkraj
                                ;");
  
    $stmt->execute();

    $miasta = $stmt->fetchAll();

    // Wypisz kraje z miastami jako odpowiedź w formacie JSON
    echo json_encode($miasta);
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
    
    $fields['imie'] = array_key_exists('imie', $_POST) ? validTextDB($_POST['imie']) : '';
    $fields['nazwisko'] = array_key_exists('nazwisko', $_POST) ? validTextDB($_POST['nazwisko']) : '';
    $fields['pesel'] = array_key_exists('pesel', $_POST) ? $_POST['pesel'] : '';
    $fields['rodzajdokumentu'] = array_key_exists('rodzajdokumentu', $_POST) ? $_POST['rodzajdokumentu'] : 'dowód osobisty';
    $fields['nrdokumentu'] = array_key_exists('nrdokumentu', $_POST) ? $_POST['nrdokumentu'] : '';
    $fields['telefon'] = array_key_exists('telefon', $_POST) ? $_POST['telefon'] : '';
    $fields['email'] = array_key_exists('email', $_POST) ? $_POST['email'] : '';
    $fields['kraj'] = array_key_exists('kraj', $_POST) ? validTextDB($_POST['kraj']) : '';
    $fields['miasto'] = array_key_exists('miasto', $_POST) ? validTextDB($_POST['miasto']) : '';
    $fields['ulica'] = array_key_exists('ulica', $_POST) ? validTextDB($_POST['ulica']) : '';
    $fields['nrdomu'] = array_key_exists('nrdomu', $_POST) ? $_POST['nrdomu'] : '';
    $fields['nrmieszkania'] = array_key_exists('nrmieszkania', $_POST) ? $_POST['nrmieszkania'] : '';
    $fields['kodpocztowy'] = array_key_exists('kodpocztowy', $_POST) ? $_POST['kodpocztowy'] : '';

    $fields['uwagi'] = array_key_exists('uwagi', $_POST) ? $_POST['uwagi'] : '';
    $fields['disabled'] = array_key_exists('disabled', $fields) ? $fields['disabled'] : '';

    $errors = array();
    $info = "";

    if(isset($_POST["findClient"]))
    {
        if (!empty($fields['telefon'])) {
            if (!validtel($fields['telefon'])) {
                $errors['telefon'] = 'Numer jest błędny.';
            }
        }
        else
        {
            $errors['telefon'] = 'Pole jest wymagane.';
        }

        if (empty($errors['telefon']) )
        {
            $fields['disabled'] = '';

            $check = $db->prepare("SELECT idklienta, rodzajdokumentu, nrdokumentu, pesel, imie, nazwisko, telefon, email, KR.NAZWAKRAJ AS KRAJ, M.nazwamiasto AS MIASTO, ulica, nrdomu, nrmieszkania, kodpocztowy, uwagi
                                FROM KLIENCI K
                                INNER JOIN MIASTO M ON K.IDMIASTO = M.IDMIASTO
                                INNER JOIN KRAJ KR ON M.IDKRAJ = KR.IDKRAJ
                                WHERE telefon = :telefon;");

            $check->bindValue(':telefon', $fields['telefon']);
            $check->execute();
            $clientInfo = $check->fetch();


            if ($clientInfo) 
            {
                $row['idklienta'] = $clientInfo['idklienta'];
                $fields['disabled'] = 'disabled';
                $fields['imie'] = $clientInfo['imie'];
                $fields['nazwisko'] = $clientInfo['nazwisko'];
                $fields['pesel'] = $clientInfo['pesel'];
                $fields['rodzajdokumentu'] = $clientInfo['rodzajdokumentu'];
                $fields['nrdokumentu'] = $clientInfo['nrdokumentu'];
                $fields['telefon'] = $clientInfo['telefon'];
                $fields['kraj'] = $clientInfo['kraj'];
                $fields['miasto'] = $clientInfo['miasto'];
                $fields['ulica'] = $clientInfo['ulica'];
                $fields['nrdomu'] = $clientInfo['nrdomu'];
                $fields['nrmieszkania'] = $clientInfo['nrmieszkania'];
                $fields['kodpocztowy'] = $clientInfo['kodpocztowy'];
            }
        }
    }


    if (isset($_POST["acceptAdd"])) {

        if (empty($fields['datapoczatek'])) {
            $errors['all'] = 'Data rozpoczęcia jest wymagana.';
        }
        else{
            if($fields['datapoczatek'] < $today)
            {
                $errors['all'] = 'Data rozpoczęcia nie może być wcześniej niż dzisiejsza '.$today;
            }
        }

        if ($fields['datakoniec'] != null || $fields['datakoniec'] != '') {
            if ($fields['datapoczatek'] > $fields['datakoniec']) {
                $errors['all'] = 'Data rozpoczęcia nie może być późniejsza niż data zakończenia.';
            }
        }

        if (empty($fields['przebiegstart'])) {
            $errors['przebiegstart'] = 'Pole jest wymagane.';
        }
        else{
            if($fields['przebiegstart'] < $row['przebiegstart'])
            {
                $errors['przebiegstart'] = "Podany przebieg jest mniejszy od aktualnego ( ".$row['przebiegstart']." km)";
            }
        }

        if (empty($fields['imie'])) {
            $errors['imie'] = 'Imię jest wymagane.';
        }

        if (empty($fields['nazwisko'])) {
            $errors['nazwisko'] = 'Nazwisko jest wymagane.';
        }

        if (empty($fields['rodzajdokumentu'])) {
            $errors['rodzajdokumentu'] = 'Rodzaj dokumentu jest wymagany.';
        }

        if (empty($fields['nrdokumentu'])) {
            $errors['nrdokumentu'] = 'Numer dokumentu jest wymagany.';
        }

        if (!empty($fields['telefon'])) {
            if (!validtel($fields['telefon'])) {
                $errors['telefon'] = 'Numer jest błędny.';
            }
        }
        else
        {
            $errors['telefon'] = 'Telefon jest wymagany.';
        }

        if (empty($fields['kraj'])) {
            $errors['kraj'] = 'Kraj jest wymagany.';
        }

        if (empty($fields['miasto'])) {
            $errors['miasto'] = 'Miasto jest wymagane.';
        }

        if (empty($fields['ulica'])) {
            $errors['ulica'] = 'Ulica jest wymagana.';
        }

        if (empty($fields['nrdomu'])) {
            $errors['nrdomu'] = 'Nr domu jest wymagany.';
        }

        if (empty($fields['kodpocztowy'])) {
            $errors['kodpocztowy'] = 'Kod pocztowy jest wymagany.';
        }

        if (empty($fields['uwagi'])) {
            $_POST['uwagi'] = 'Brak';
        }

        

        if (count($errors) == 0) {

            try {

                $db->beginTransaction();

                //Jeśli klienta nie ma w bazie wykonujemy procedurę jego dodania
                if(!isset($row['idklienta']))
                {
                    //Sprawdzamy czy kraj klienta występuje w tabeli kraj
                    $pstmt = $db->prepare("SELECT IDKRAJ FROM KRAJ WHERE NAZWAKRAJ=:kraj;");
                    $pstmt->bindValue(':kraj', $fields['kraj']);
                    $pstmt->execute();
                    $idkraj = $pstmt->fetchColumn();

                    //Jeśli nie to dodajemy kraj do bazy i pobieramy jego id
                    if(!isset($idkraj))
                    {
                        $pstmt = $db->prepare("INSERT INTO KRAJ( NAZWAKRAJ) VALUES :kraj;");
                        $pstmt->bindValue(':kraj', $fields['kraj']);
                        $pstmt->execute();

                        $idkraj = $db->lastInsertId();
                    }

                    //Sprawdzamy czy miasto klienta występuje w tabeli miasto
                    $pstmt = $db->prepare("SELECT IDMIASTO FROM MIASTO WHERE NAZWAMIASTO=:miasto;");
                    $pstmt->bindValue(':miasto', $fields['miasto']);
                    $pstmt->execute();
                    $idmiasto = $pstmt->fetchColumn();

                    //Jeśli nie to dodajemy miasto do bazy i pobieramy jego id
                    if(!isset($idmiasto))
                    {
                        $pstmt = $db->prepare("INSERT INTO MIASTO( IDKRAJ, NAZWAMIASTO) VALUES :idkraj, :miasto;");
                        $pstmt->bindValue(':idkraj', $idkraj, PDO::PARAM_INT);
                        $pstmt->bindValue(':miasto', $fields['miasto']);
                        $pstmt->execute();

                        $idmiasto = $db->lastInsertId();
                    }

                    //Dodawanie klienta do bazy
                    $clientstmt = $db->prepare("INSERT INTO klienci(rodzajdokumentu, nrdokumentu, pesel, imie, nazwisko, telefon, email, idmiasto, ulica, nrdomu, nrmieszkania, kodpocztowy)
                    VALUES (:rodzajdokumentu, :nrdokumentu, :pesel, :imie, :nazwisko, :telefon, :email, :miasto, :ulica, :nrdomu, :nrmieszkania, :kodpocztowy);");

                    $clientstmt->bindValue(':rodzajdokumentu', $fields['rodzajdokumentu']);
                    $clientstmt->bindValue(':nrdokumentu', $fields['nrdokumentu']);
                    $clientstmt->bindValue(':pesel', $fields['pesel']);
                    $clientstmt->bindValue(':imie', $fields['imie']);
                    $clientstmt->bindValue(':nazwisko', $fields['nazwisko']);
                    $clientstmt->bindValue(':telefon', $fields['telefon']);
                    $clientstmt->bindValue(':email', $fields['email']);
                    $clientstmt->bindValue(':miasto', $idmiasto, PDO::PARAM_INT);
                    $clientstmt->bindValue(':ulica', $fields['ulica']);
                    $clientstmt->bindValue(':nrdomu', $fields['nrdomu']);
                    $clientstmt->bindValue(':nrmieszkania', $fields['nrmieszkania']);
                    $clientstmt->bindValue(':kodpocztowy', $fields['kodpocztowy']);

                    $clientstmt->execute();

                    $row['idklienta'] = $db->lastInsertId();
                }

                //Dodanie wypożyczenia do bazy
                $stmt = $db->prepare("INSERT INTO public.wypozyczenia(
                    idauta, idklienta, idpracownika, datapoczatek, datakoniec, przebiegstart, zaplacono, uwagi)
                    VALUES (:idauta, :idklienta, :idpracownika, :datapoczatek, :datakoniec, :przebiegstart, FALSE, :uwagi);");
                
                $stmt->bindValue(':idauta', $row['idauto'], PDO::PARAM_INT);
                $stmt->bindValue(':idklienta', $row['idklienta'], PDO::PARAM_INT);
                $stmt->bindValue(':idpracownika', $_SESSION['uid'], PDO::PARAM_INT);
                $stmt->bindValue(':datapoczatek', $fields['datapoczatek']);
                $stmt->bindValue(':datakoniec', $fields['datakoniec']);
                $stmt->bindValue(':przebiegstart', $fields['przebiegstart'], PDO::PARAM_INT);
                $stmt->bindValue(':uwagi', $fields['uwagi']);
                

                $stmt->execute();

                $db->commit();

            } catch (PDOException $e) {
                $db->rollBack();
                $errors['all'] = "Błąd: " . $e->getMessage();
                $info = "";
            }

            if (count($errors) == 0) {

                $newid = $db->lastInsertId();
                console_log("Dane dodane pomyślnie");
                unset($_SESSION['rentAdd']);
                redirect(url("rentList&value={$newid}&event=details"));
            }
        }
    }
}