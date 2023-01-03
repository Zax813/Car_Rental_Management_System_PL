<?php

if((array_key_exists('event', $_GET)))
{
    if($_GET['event']=='list')
    {
        redirect(url('rentList'));
    }
}

// Pobierz aktualną datę w formacie YYYY-MM-DD
$today = date("Y-m-d");


if (isset($_SESSION['rentFinal'])) {

    $check = $db ->query("SELECT IDWYPOZYCZENIA, A.IDAUTO, A.REJESTRACJA AS NUMER, MR.NAZWAMARKI AS MARKA, MD.NAZWAMODEL AS MODEL, A.CENADOBA, A.CENAKM, K.RODZAJDOKUMENTU, K.NRDOKUMENTU, K.PESEL, K.IMIE, K.NAZWISKO, K.TELEFON, K.EMAIL, IDPRACOWNIKA, DATAPOCZATEK, DATAKONIEC, PRZEBIEGSTART, PRZEBIEGKONIEC, SUMA, ZAPLACONO, W.UWAGI
                            FROM WYPOZYCZENIA W 
                            INNER JOIN AUTA A ON A.IDAUTO = W.IDAUTA
                            INNER JOIN MODEL MD ON A.IDMODEL=MD.IDMODEL
                            INNER JOIN MARKA MR ON MD.IDMARKA=MR.IDMARKA
                            INNER JOIN KLIENCI K ON K.IDKLIENTA = W.IDKLIENTA
                            WHERE IDWYPOZYCZENIA = {$_SESSION['rentFinal']};");

    $row = $check -> fetch();
    
    $row['samochod'] = $row['marka'].' '.$row['model'];


    $fields['datapoczatek'] = array_key_exists('datapoczatek', $_POST) ? $_POST['datapoczatek'] : $row['datapoczatek'];
    $fields['datakoniec'] = array_key_exists('datakoniec', $_POST) ? $_POST['datakoniec'] : $today;

    $fields['samochod'] = array_key_exists('samochod', $_POST) ? $_POST['samochod'] : $row['samochod'];
    $fields['numer'] = array_key_exists('numer', $_POST) ? $_POST['numer'] : $row['numer'];
    $fields['przebiegstart'] = array_key_exists('przebiegstart', $_POST) ? $_POST['przebiegstart'] : $row['przebiegstart'];
    $fields['przebiegkoniec'] = array_key_exists('przebiegkoniec', $_POST) ? $_POST['przebiegkoniec'] : $row['przebiegkoniec'];
    
    $fields['imie'] = array_key_exists('imie', $_POST) ? validTextDB($_POST['imie']) : $row['imie'];
    $fields['nazwisko'] = array_key_exists('nazwisko', $_POST) ? validTextDB($_POST['nazwisko']) : $row['nazwisko'];
    $fields['pesel'] = array_key_exists('pesel', $_POST) ? $_POST['pesel'] : $row['pesel'];
    $fields['rodzajdokumentu'] = array_key_exists('rodzajdokumentu', $_POST) ? $_POST['rodzajdokumentu'] : $row['rodzajdokumentu'];
    $fields['nrdokumentu'] = array_key_exists('nrdokumentu', $_POST) ? $_POST['nrdokumentu'] : $row['nrdokumentu'];
    $fields['telefon'] = array_key_exists('telefon', $_POST) ? $_POST['telefon'] : $row['telefon'];
    $fields['email'] = array_key_exists('email', $_POST) ? $_POST['email'] : $row['email'];
    $fields['suma'] = array_key_exists('suma', $_POST) ? $_POST['suma'] : $row['suma'];
    $fields['obliczenia'] = array_key_exists('obliczenia', $_POST) ? $_POST['obliczenia'] : '';
    $fields['zaplacono'] = array_key_exists('zaplacono', $_POST) ? $_POST['zaplacono'] : $row['zaplacono'];


    $fields['uwagi'] = array_key_exists('uwagi', $_POST) ? $_POST['uwagi'] : '';
    $fields['disabled'] = array_key_exists('disabled', $fields) ? $fields['disabled'] : '';

    $errors = array();
    $info = "";

    if(isset($_POST["calcPrice"]))
    {
        if (empty($fields['przebiegkoniec'])) {
            $errors['przebiegkoniec'] = 'Przebieg końcowy jest wymagany.';
        }
        
        if (empty($fields['datakoniec'])) {
            $errors['datakoniec'] = 'Data zakończenia wypożyczenia jest wymagana.';
        }

        if (empty($errors['przebiegkoniec']) && empty($errors['datakoniec']))
        {

            $dni = (strtotime($fields['datakoniec']) - strtotime($row['datapoczatek'])) / 60 / 60 / 24;
            $przejechano = $fields['przebiegkoniec'] - $fields['przebiegstart'];

            $kosztDoba = $dni * $row['cenadoba'];
            $kosztKM = $przejechano * $row['cenakm'];
            $sumaKoszt = $kosztDoba + $kosztKM;

            $fields['suma'] = $sumaKoszt;
            $fields['obliczenia'] = "Koszt 24h: ".$kosztDoba." zł \nKoszt km:  ".$kosztKM." zł \nSuma: ".$sumaKoszt." zł";

        }
    }

    if(isset($_POST["acceptFinal"]))
    {
        if (empty($fields['przebiegkoniec'])) {
            $errors['przebiegkoniec'] = 'Przebieg końcowy jest wymagany.';
        }
        
        if (empty($fields['datakoniec'])) {
            $errors['datakoniec'] = 'Data zakończenia wypożyczenia jest wymagana.';
        }

        if(empty($fields['suma']))
        {
            $errors['suma'] = 'Obliczenie lub podanie kosztu wypożyczenia jest wymagane.';
        }

        if (count($errors) == 0)
        {

            $dni = (strtotime($fields['datakoniec']) - strtotime($row['datapoczatek'])) / 60 / 60 / 24;
            $przejechano = $fields['przebiegkoniec'] - $fields['przebiegstart'];

            $kosztDoba = $dni * $row['cenadoba'];
            $kosztKM = $przejechano * $row['cenakm'];
            $sumaKoszt = $kosztDoba + $kosztKM;

            $fields['obliczenia'] = "\nKoszt 24h: ".$kosztDoba." zł \nKoszt km:  ".$kosztKM." zł \nSuma: ".$sumaKoszt." zł \nZatwierdzono: ".$fields['suma']." zł\n--------------------\n";

            $uwagi = $fields['obliczenia'].$row['uwagi']."\nOdbiór: ".$fields['uwagi'];

            try {

                $db->beginTransaction();

                $stmt = $db->prepare("UPDATE wypozyczenia
                                    SET datakoniec=:datakoniec, przebiegkoniec=:przebiegkoniec, suma=:suma, zaplacono=TRUE, uwagi=:uwagi
                                    WHERE idwypozyczenia={$_SESSION['rentFinal']};");

                $stmt->bindValue(':datakoniec', $fields['datakoniec']);
                $stmt->bindValue(':przebiegkoniec', $fields['przebiegkoniec']);
                $stmt->bindValue(':suma', $fields['suma']);
                $stmt->bindValue(':uwagi', $uwagi);
                $stmt->execute();

                $stmt = $db->prepare("UPDATE AUTA
                                    SET przebieg=:przebiegkoniec, dostepny=TRUE
                                    WHERE idauto={$row['idauto']};");
                $stmt->bindValue(':przebiegkoniec', $fields['przebiegkoniec']);
                $stmt->execute();

                $db->commit();

            } catch (PDOException $e) {
                $db->rollBack();
                $errors['all'] = "Błąd: " . $e->getMessage();
                $info = "";
            }

            if (count($errors) == 0) {

                $newid = $_SESSION['rentFinal'];
                console_log("Dane zaktualizowane pomyślnie");
                unset($_SESSION['rentFinal']);
                redirect(url("rentList&value={$newid}&event=details"));
            }
        }
    }

}