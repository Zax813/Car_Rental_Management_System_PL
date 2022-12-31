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
                $fields['disabled'] = true;
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

        if ($fields['datakoniec'] != null || $fields['datakoniec'] != '') {
            if ($fields['datapoczatek'] > $fields['datakoniec']) {
                $errors['all'] = 'Data rozpoczęcia nie może być późniejsza niż data zakończenia.';
            }
        }

        if (empty($fields['przebiegstart'])) {
            $errors['przebiegstart'] = 'Pole jest wymagane.';
        }

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

        if (!empty($fields['telefon'])) {
            if (!validtel($fields['telefon'])) {
                $errors['telefon'] = 'Numer jest błędny.';
            }
        }
        else
        {
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

        

        if (count($errors) == 0) {

            try {

                $db->beginTransaction();

                //Jeśli klienta nie ma w bazie wykonujemy procedurę jego dodania
                if(!isset($row['idklienta']))
                {
                    //Sprawdzamy czy kraj klienta występuje w tabeli kraj
                    $pstmt = $db->prepare("SELECT IDKRAJ FROM KRAJ WHERE NAZWAKRAJ=:kraj;");
                    $pstmt->bindValue(':kraj', $_POST['kraj']);
                    $pstmt->execute();
                    $idkraj = $pstmt->fetchColumn();

                    //Jeśli nie to dodajemy kraj do bazy i pobieramy jego id
                    if(!isset($idkraj))
                    {
                        $pstmt = $db->prepare("INSERT INTO KRAJ( NAZWAKRAJ) VALUES :kraj;");
                        $pstmt->bindValue(':kraj', $_POST['kraj']);
                        $pstmt->execute();

                        $idkraj = $db->lastInsertId();
                    }

                    //Sprawdzamy czy miasto klienta występuje w tabeli miasto
                    $pstmt = $db->prepare("SELECT IDMIASTO FROM MIASTO WHERE NAZWAMIASTO=:miasto;");
                    $pstmt->bindValue(':miasto', $_POST['miasto']);
                    $pstmt->execute();
                    $idmiasto = $pstmt->fetchColumn();

                    //Jeśli nie to dodajemy miasto do bazy i pobieramy jego id
                    if(!isset($idmiasto))
                    {
                        $pstmt = $db->prepare("INSERT INTO MIASTO( IDKRAJ, NAZWAMIASTO) VALUES :idkraj, :miasto;");
                        $pstmt->bindValue(':idkraj', $idkraj, PDO::PARAM_INT);
                        $pstmt->bindValue(':miasto', $_POST['miasto']);
                        $pstmt->execute();

                        $idmiasto = $db->lastInsertId();
                    }

                    //Dodawanie klienta do bazy
                    $clientstmt = $db->prepare("INSERT INTO klienci(rodzajdokumentu, nrdokumentu, pesel, imie, nazwisko, telefon, email, idmiasto, ulica, nrdomu, nrmieszkania, kodpocztowy)
                    VALUES (:rodzajdokumentu, :nrdokumentu, :pesel, :imie, :nazwisko, :telefon, :email, :miasto, :ulica, :nrdomu, :nrmieszkania, :kodpocztowy);");

                    $clientstmt->bindValue(':rodzajdokumentu', $_POST['rodzajdokumentu']);
                    $clientstmt->bindValue(':nrdokumentu', $_POST['nrdokumentu']);
                    $clientstmt->bindValue(':pesel', $_POST['pesel']);
                    $clientstmt->bindValue(':imie', $_POST['imie']);
                    $clientstmt->bindValue(':nazwisko', $_POST['nazwisko']);
                    $clientstmt->bindValue(':telefon', $_POST['telefon']);
                    $clientstmt->bindValue(':email', $_POST['email']);
                    $clientstmt->bindValue(':miasto', $idmiasto, PDO::PARAM_INT);
                    $clientstmt->bindValue(':ulica', $_POST['ulica']);
                    $clientstmt->bindValue(':nrdomu', $_POST['nrdomu']);
                    $clientstmt->bindValue(':nrmieszkania', $_POST['nrmieszkania']);
                    $clientstmt->bindValue(':kodpocztowy', $_POST['kodpocztowy']);

                    $clientstmt->execute();

                    $row['idklienta'] = $db->lastInsertId();
                }

                $stmt = $db->prepare("INSERT INTO public.wypozyczenia(
                    idauta, idklienta, idpracownika, datapoczatek, datakoniec, przebiegstart, zaplacono, uwagi)
                    VALUES (:idauta, :idklienta, :idpracownika, :datapoczatek, :datakoniec, :przebiegstart, FALSE, :uwagi);");
                
                $stmt->bindValue(':idauta', $row['idauto'], PDO::PARAM_INT);
                $stmt->bindValue(':idklienta', $row['idklienta'], PDO::PARAM_INT);
                $stmt->bindValue(':idpracownika', $_SESSION['uid'], PDO::PARAM_INT);
                $stmt->bindValue(':datapoczatek', $_POST['datapoczatek']);
                $stmt->bindValue(':datakoniec', $_POST['datakoniec']);
                $stmt->bindValue(':przebiegstart', $_POST['przebiegstart'], PDO::PARAM_INT);
                $stmt->bindValue(':uwagi', $_POST['uwagi']);
                

                $stmt->execute();

                $db->commit();

            } catch (PDOException $e) {
                $db->rollBack();
                $errors['all'] = "Błąd: " . $e->getMessage();
                $info = "";
            }

            if (count($errors) == 0) {
                $newid = $db->lastInsertId();
                redirect(url("rentList&value={$newid}&event=details"));
                $info = "Przegląd dodany pomyślnie";
            }
        }
    }
}