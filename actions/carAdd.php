<?php

function getBrandData($db) {
    
    // Pobierz dostępne kraje z bazy danych
    $stmt = $db->prepare("SELECT nazwamarki AS marka FROM marka;");
    $stmt->execute();

    $marki = $stmt->fetchAll();

    $marki_values = array_map(function($marka) {
        return $marka['marka'];
    }, $marki);
        
    // Wypisz marki jako odpowiedź w formacie JSON
    echo json_encode($marki_values);
}

function getModelData($db) {

        $stmt = $db->prepare("SELECT mr.nazwamarki AS marka, md.nazwamodel AS model FROM model  md
                                INNER JOIN marka mr ON mr.idmarka = md.idmarka;");
  
    $stmt->execute();
    $modele = $stmt->fetchAll();

    // Wypisz marki z modelami jako odpowiedź w formacie JSON
    echo json_encode($modele);
}


if((array_key_exists('event', $_GET)))
{
    if($_GET['event']=='list')
    {
        redirect(url('home'));
    }
}


if (isset($_SESSION['carAdd'])) {

    $stmt = $db->query('SELECT IDZDJECIE, TYTUL FROM ZDJECIA ORDER BY TYTUL;');
    $zdjecia = $stmt->fetchAll();

    $stmt = $db->query('SELECT * FROM SEGMENT ORDER BY NAZWASEGMENT;');
    $segmenty = $stmt->fetchAll();

    $stmt = $db->query('SELECT * FROM PALIWO ORDER BY NAZWAPALIWO;');
    $paliwa = $stmt->fetchAll();


    if(isset($_SESSION['carAddStorage']))
    {
        $fields = $_SESSION['carAddStorage'];
        unset($_SESSION['carAddStorage']);
    }
    else
    {
        $fields['marka'] = array_key_exists('marka', $_POST) ? $_POST['marka'] : '';
        $fields['model'] = array_key_exists('model', $_POST) ? $_POST['model'] : '';
        $fields['vin'] = array_key_exists('vin', $_POST) ? $_POST['vin'] : '';
        $fields['numer'] = array_key_exists('numer', $_POST) ? $_POST['numer'] : '';
        $fields['segment'] = array_key_exists('segment', $_POST) ? $_POST['segment'] : '';
        $fields['paliwo'] = array_key_exists('paliwo', $_POST) ? $_POST['paliwo'] : '';
        $fields['mockw'] = array_key_exists('mockw', $_POST) ? $_POST['mockw'] : '';
        $fields['skrzynia'] = array_key_exists('skrzynia', $_POST) ? $_POST['skrzynia'] : '';
        $fields['liczbamiejsc'] = array_key_exists('liczbamiejsc', $_POST) ? $_POST['liczbamiejsc'] : '';
        $fields['rok'] = array_key_exists('rok', $_POST) ? $_POST['rok'] : '';
        $fields['przebieg'] = array_key_exists('przebieg', $_POST) ? $_POST['przebieg'] : '';  
        $fields['cenakm'] = array_key_exists('cenakm', $_POST) ? $_POST['cenakm'] : '';
        $fields['cenadoba'] = array_key_exists('cenadoba', $_POST) ? $_POST['cenadoba'] : '';
        $fields['uwagi'] = array_key_exists('uwagi', $_POST) ? $_POST['uwagi'] : '';
        $fields['zdjecie'] = array_key_exists('zdjecie', $_POST) ? $_POST['zdjecie'] : '';
    }

    $errors = array();
    $info = '';

    if(isset($_POST["addPhoto"]))
    {
        $_SESSION['carAddStorage'] = $fields;
        redirect(url('imageAdd'));
    }

    if(isset($_POST["acceptAdd"]))
    {

        if(empty($fields['marka']))
        {
            $errors['marka'] = 'Pole marka nie może być puste';
        }
        else
        {
            $fields['marka'] = validTextDB($fields['marka']);
            $_POST['marka'] = $fields['marka'];
        }

        if(empty($fields['model']))
        {
            $errors['model'] = 'Pole model nie może być puste';
        }
        else
        {
            $fields['model'] = validTextDB($fields['model']);
            $_POST['model'] = $fields['model'];
        }

        if(empty($fields['vin']))
        {
            $errors['vin'] = 'Pole VIN nie może być puste';
        }

        if(empty($fields['numer']))
        {
            $errors['numer'] = 'Pole numer rejestracyjny nie może być puste';
        }

        if(empty($fields['segment']))
        {
            $errors['segment'] = 'Pole segment nie może być puste';
        }

        if(empty($fields['paliwo']))
        {
            $errors['paliwo'] = 'Pole paliwo nie może być puste';
        }

        if(empty($fields['mockw']))
        {
            $errors['mockw'] = 'Pole moc/kw nie może być puste';
        }

        if(empty($fields['skrzynia']))
        {
            $errors['skrzynia'] = 'Pole skrzynia biegów nie może być puste';
        }

        if(empty($fields['liczbamiejsc']))
        {
            $errors['liczbamiejsc'] = 'Pole liczba miejsc nie może być puste';
        }

        if(empty($fields['rok']))
        {
            $errors['rok'] = 'Pole rok produkcji nie może być puste';
        }

        if(empty($fields['przebieg']))
        {
            $errors['przebieg'] = 'Pole przebieg nie może być puste';

        }

        if(empty($fields['cenakm']))
        {
            $errors['cenakm'] = 'Pole cena za kilometr nie może być puste';
        }

        if(empty($fields['cenadoba']))
        {
            $errors['cenadoba'] = 'Pole cena za dobę nie może być puste';
        }

        if(empty($fields['uwagi']))
        {
            $fields['uwagi'] = '';
        }

        if(empty($fields['zdjecie']))
        {
            $fields['zdjecie'] = null;
        }

        if(count($errors) == 0)
        {
            try{
                $db -> beginTransaction();

                $pstmt = $db->prepare('SELECT IDMARKA FROM MARKA WHERE NAZWAMARKI = :marka');
                $pstmt -> bindValue(':marka', $fields['marka'], PDO::PARAM_STR);
                $pstmt -> execute();

                $idmarka = $pstmt -> fetch(PDO::FETCH_ASSOC);

                if(empty($idmarka))
                {
                    $pstmt = $db->prepare('INSERT INTO MARKA (NAZWAMARKI) VALUES (:marka)');
                    $pstmt -> bindValue(':marka', $fields['marka'], PDO::PARAM_STR);
                    $pstmt -> execute();

                    $idmarka = $db->lastInsertId();
                }
                else
                {
                    $idmarka = $idmarka['idmarka'];
                }

                $pstmt = $db->prepare('SELECT IDMODEL FROM MODEL WHERE NAZWAMODEL = :model');
                $pstmt -> bindValue(':model', $fields['model'], PDO::PARAM_STR);
                $pstmt -> execute();

                $idmodel = $pstmt -> fetch(PDO::FETCH_ASSOC);

                if(empty($idmodel))
                {
                    $pstmt = $db->prepare('INSERT INTO MODEL (NAZWAMODEL, IDMARKA) VALUES (:model, :idmarka)');
                    $pstmt -> bindValue(':model', $fields['model'], PDO::PARAM_STR);
                    $pstmt -> bindValue(':idmarka', $idmarka, PDO::PARAM_INT);
                    $pstmt -> execute();

                    $idmodel = $db->lastInsertId();
                }
                else
                {
                    $idmodel = intval($idmodel['IDMODEL']);
                }

                $stmt = $db->prepare('INSERT INTO AUTA ( IDMODEL, VIN, REJESTRACJA, IDSEGMENT, IDPALIWO, MOCKW, SKRZYNIA, LICZBAMIEJSC, ROK, PRZEBIEG, CENAKM, CENADOBA, UWAGI, IDZDJECIE) 
                                        VALUES ( :model, :vin, :numer, :segment, :paliwo, :mockw, :skrzynia, :liczbamiejsc, :rok, :przebieg, :cenakm, :cenadoba, :uwagi, :zdjecie);');
                
                $stmt -> bindValue(':model', $idmodel, PDO::PARAM_INT);
                $stmt -> bindValue(':vin', strtoupper($fields['vin']));
                $stmt -> bindValue(':numer', strtoupper($fields['numer']));
                $stmt -> bindValue(':segment', $fields['segment'], PDO::PARAM_INT);
                $stmt -> bindValue(':paliwo', $fields['paliwo'], PDO::PARAM_INT);
                $stmt -> bindValue(':mockw', $fields['mockw'], PDO::PARAM_INT);
                $stmt -> bindValue(':skrzynia', $fields['skrzynia'], PDO::PARAM_INT);
                $stmt -> bindValue(':liczbamiejsc', $fields['liczbamiejsc'], PDO::PARAM_INT);
                $stmt -> bindValue(':rok', $fields['rok'], PDO::PARAM_INT);
                $stmt -> bindValue(':przebieg', $fields['przebieg'], PDO::PARAM_INT);
                $stmt -> bindValue(':cenakm', $fields['cenakm']);
                $stmt -> bindValue(':cenadoba', $fields['cenadoba']);
                $stmt -> bindValue(':uwagi', $fields['uwagi'], PDO::PARAM_STR);
                $stmt -> bindValue(':zdjecie', $fields['zdjecie'], PDO::PARAM_INT);
                $stmt -> execute();

                $db->commit();

            }
            catch(PDOException $e)
            {
                $db->rollBack();
                $errors['all'] = 'Błąd: ' . $e->getMessage();
            }

            if (count($errors) == 0) 
            {

                $newid = $db->lastInsertId();
                console_log("Dane dodane pomyślnie");
                unset($_SESSION['rentAdd']);
                redirect(url("home&value={$newid}&event=details"));
            }

        }

    }

}