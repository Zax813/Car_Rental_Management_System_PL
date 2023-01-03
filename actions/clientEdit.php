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
                                INNER JOIN kraj k ON k.idkraj = m.idkraj;");
  
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

if (isset($_SESSION['clientEdit'])) {

    $stmt = $db ->prepare("SELECT idklienta, rodzajdokumentu, nrdokumentu, pesel, imie, nazwisko, telefon, email, KR.NAZWAKRAJ AS KRAJ, M.nazwamiasto AS MIASTO, ulica, nrdomu, nrmieszkania, kodpocztowy, uwagi
                            FROM KLIENCI K
                            INNER JOIN MIASTO M ON K.IDMIASTO = M.IDMIASTO
                            INNER JOIN KRAJ KR ON M.IDKRAJ = KR.IDKRAJ
                            WHERE idklienta = :idklienta;");

    $stmt->bindValue(':idklienta', $_SESSION['clientEdit']);
    $stmt->execute();

    $row = $stmt -> fetch();

    $fields['imie'] = array_key_exists('imie', $_POST) ? validTextDB($_POST['imie']) : $row['imie'];
    $fields['nazwisko'] = array_key_exists('nazwisko', $_POST) ? validTextDB($_POST['nazwisko']) : $row['nazwisko'];
    $fields['pesel'] = array_key_exists('pesel', $_POST) ? $_POST['pesel'] : $row['pesel'];
    $fields['rodzajdokumentu'] = array_key_exists('rodzajdokumentu', $_POST) ? $_POST['rodzajdokumentu'] : $row['rodzajdokumentu'];
    $fields['nrdokumentu'] = array_key_exists('nrdokumentu', $_POST) ? $_POST['nrdokumentu'] : $row['nrdokumentu'];
    $fields['telefon'] = array_key_exists('telefon', $_POST) ? $_POST['telefon'] : $row['telefon'];
    $fields['email'] = array_key_exists('email', $_POST) ? $_POST['email'] : $row['email'];
    $fields['kraj'] = array_key_exists('kraj', $_POST) ? validTextDB($_POST['kraj']) : $row['kraj'];
    $fields['miasto'] = array_key_exists('miasto', $_POST) ? validTextDB($_POST['miasto']) : $row['miasto'];
    $fields['ulica'] = array_key_exists('ulica', $_POST) ? validTextDB($_POST['ulica']) : $row['ulica'];
    $fields['nrdomu'] = array_key_exists('nrdomu', $_POST) ? $_POST['nrdomu'] : $row['nrdomu'];
    $fields['nrmieszkania'] = array_key_exists('nrmieszkania', $_POST) ? $_POST['nrmieszkania'] : $row['nrmieszkania'];
    $fields['kodpocztowy'] = array_key_exists('kodpocztowy', $_POST) ? $_POST['kodpocztowy'] : $row['kodpocztowy'];

    $fields['disabled'] = ''; 

    $errors = array();
    $info = "";


    if (isset($_POST["acceptAdd"])) {


        if (!empty($fields['telefon'])) {
            if (!validtel($fields['telefon'])) {
                $errors['telefon'] = 'Numer jest błędny.';
            }
        } else {
            $errors['telefon'] = 'Telefon jest wymagany.';
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
        

        if (count($errors) == 0) {

            try {

                $db->beginTransaction();

                //Sprawdzamy czy kraj klienta występuje w tabeli kraj
                $pstmt = $db->prepare("SELECT IDKRAJ FROM KRAJ WHERE NAZWAKRAJ=:kraj;");
                $pstmt->bindValue(':kraj', $fields['kraj']);
                $pstmt->execute();
                $idkraj = $pstmt->fetchColumn();

                //Jeśli nie to dodajemy kraj do bazy i pobieramy jego id
                if (!isset($idkraj)) {
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
                if (!isset($idmiasto)) {
                    $pstmt = $db->prepare("INSERT INTO MIASTO( IDKRAJ, NAZWAMIASTO) VALUES :idkraj, :miasto;");
                    $pstmt->bindValue(':idkraj', $idkraj, PDO::PARAM_INT);
                    $pstmt->bindValue(':miasto', $fields['miasto']);
                    $pstmt->execute();

                    $idmiasto = $db->lastInsertId();
                }

                //Dodawanie klienta do bazy
                $clientstmt = $db->prepare("UPDATE klienci
                    SET rodzajdokumentu = :rodzajdokumentu, nrdokumentu = :nrdokumentu, pesel = :pesel, imie = :imie, nazwisko = :nazwisko, telefon = :telefon, email = :email, idmiasto = :miasto, ulica = :ulica, nrdomu = :nrdomu, nrmieszkania = :nrmieszkania, kodpocztowy = :kodpocztowy
                    WHERE idklienta = :idklienta;");

                $clientstmt->bindValue(':idklienta', $_SESSION['clientEdit']);
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

                $db->commit();

            } catch (PDOException $e) 
            {
                $db->rollBack();
                $errors['all'] = "Błąd: " . $e->getMessage();
                $info = "";
            }

            if (count($errors) == 0) {
                $newid = $_SESSION['clientEdit'];
                console_log("Dane dodane pomyślnie");
                unset($_SESSION['clientEdit']);
                redirect(url("clientList&value={$newid}&event=details"));
            }
        }
    }
}