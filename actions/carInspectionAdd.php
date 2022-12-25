<?php

function getData($db) {
    
    // Pobierz dostępne numery rejestracyjne z bazy danych
    $stmt = $db->prepare("SELECT rejestracja FROM auta WHERE SPRAWNY = TRUE;");
    $stmt->execute();

    $rejestracje = $stmt->fetchAll();

    $rejestracje_values = array_map(function($rejestracja) {
        return $rejestracja['rejestracja'];
    }, $rejestracje);
        
    // Wypisz numery rejestracyjne jako odpowiedź w formacie JSON
    echo json_encode($rejestracje_values);
}


if((array_key_exists('event', $_GET)))
{
    if($_GET['event']=='list')
    {
        redirect(url('carInspectionList'));
    }
}

// Pobierz aktualną datę w formacie YYYY-MM-DD
$today = date("Y-m-d");

// Dodaj rok do przodu
$nextYear = date("Y-m-d", strtotime("+1 year"));


$fields['numer'] = array_key_exists('numer', $_POST) ? $_POST['numer'] : '';
$fields['dataprzeglad'] = array_key_exists('dataprzeglad', $_POST) ? $_POST['dataprzeglad'] : '';
$fields['waznosc'] = array_key_exists('waznosc', $_POST) ? $_POST['waznosc'] : '';
$fields['uwagi'] = array_key_exists('uwagi', $_POST) ? $_POST['uwagi'] : '';

$errors = array();
$info = "";


if (isset($_POST["acceptAdd"])) {

    if (empty($fields['numer'])) {
        $errors['numer'] = 'Pole jest wymagane.';
    }

    if (empty($fields['dataprzeglad'])) {
        $errors['dataprzeglad'] = 'Pole jest wymagane.';
    }

    if (empty($fields['waznosc'])) {
        $errors['waznosc'] = 'Pole jest wymagane.';
    }

    if (empty($fields['uwagi'])) {
        $_POST['uwagi'] = 'Brak';
    }

    if (count($errors) == 0) 
    {

        try 
        {
            $db->beginTransaction();

            $pstmt = $db->prepare("SELECT IDAUTO FROM AUTA WHERE REJESTRACJA=:numer;");
            $pstmt->bindValue(':numer', $_POST['numer']);
            $pstmt->execute();
            $idauta = $pstmt->fetchColumn();

            if(!$idauta)
            {
                $errors['all'] = "Błąd: Nie znaleziono samochodu o podanej rejestracji w bazie";
            }
            else
            {
                $ustmt = $db->prepare("INSERT INTO przeglad( idauta, dataprzegladu, datawaznosci, uwagi)
                                        VALUES ( :id, :dataprzeglad, :waznosc, :uwagi);");

                $ustmt->bindValue(':id', $idauta, PDO::PARAM_INT);
                $ustmt->bindValue(':dataprzeglad', $_POST['dataprzeglad']);
                $ustmt->bindValue(':waznosc', $_POST['waznosc']);
                $ustmt->bindValue(':uwagi', $_POST['uwagi']);
                $ustmt->execute();
            }

            $db->commit();

        } 
        catch (PDOException $e)
        {
            $db->rollBack();
            $errors['all'] = "Błąd: " . $e->getMessage();
            $info = "";
        }

        if (count($errors) == 0) 
        {
            $info = "Przegląd dodany pomyślnie";
        }
    }
}
