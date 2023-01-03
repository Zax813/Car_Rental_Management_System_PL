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
        redirect(url('carServiceList'));
    }
}


// Pobierz aktualną datę w formacie YYYY-MM-DD
$today = date("Y-m-d");

// Dodaj rok do przodu
$nextDay = date("Y-m-d", strtotime("+1 day"));

$fields['numer'] = array_key_exists('numer', $_POST) ? $_POST['numer'] : '';
$fields['datapoczatek'] = array_key_exists('datapoczatek', $_POST) ? $_POST['datapoczatek'] : '';
$fields['datakoniec'] = array_key_exists('datakoniec', $_POST) ? $_POST['datakoniec'] : '';
$fields['nazwaserwis'] = array_key_exists('nazwaserwis', $_POST) ? $_POST['nazwaserwis'] : '';
$fields['opis'] = array_key_exists('opis', $_POST) ? $_POST['opis'] : '';
$fields['uwagi'] = array_key_exists('uwagi', $_POST) ? $_POST['uwagi'] : '';
$fields['koszt'] = array_key_exists('koszt', $_POST) ? $_POST['koszt'] : '';

$errors = array();
$info = "";


if (isset($_POST["acceptAdd"])) {

    if (empty($fields['numer'])) {
        $errors['numer'] = 'Pole jest wymagane.';
    }

    if (empty($fields['datapoczatek']) || empty($fields['datakoniec'])) {
        $errors['all'] = 'Pole jest wymagane.';
    }

    if (empty($fields['nazwaserwis'])) {
        $errors['nazwaserwis'] = 'Pole jest wymagane.';
    }

    if (empty($fields['opis'])) {
        $errors['opis'] = 'Pole jest wymagane.';
    }

    if (empty($fields['uwagi'])) {
        $_POST['uwagi'] = 'Brak';
    }

    if (empty($fields['koszt'])) {
        $_POST['koszt'] = '0.00';
    }
    else{
        $_POST['koszt'] = str_replace(',', '.', $_POST['koszt']);
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

                if($_POST['datapoczatek'] == $today)
                {
                    $stmt = $db->prepare("UPDATE AUTA SET dostepny=FALSE, sprawny=FALSE
                                            WHERE IDAUTO=:idauto");
                    $stmt->bindValue(':idauto', $idauta, PDO::PARAM_INT);
                    $stmt->execute();
                }
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
