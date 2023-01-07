<?php

if((array_key_exists('event', $_GET)))
{
    if($_GET['event']=='list')
    {
        unset($_SESSION['carEdit']);
        redirect(url('home'));
    }
}

if (isset($_SESSION['carEdit'])) {

    $stmt = $db->query('SELECT IDZDJECIE, TYTUL FROM ZDJECIA ORDER BY TYTUL;');
    $zdjecia = $stmt->fetchAll();

    if(isset($_SESSION['carAddStorage']))
    {
        unset($_SESSION['carAddStorage']);
    }

    $stmt = $db->prepare('SELECT IDAUTO, VIN, REJESTRACJA, IDZDJECIE, S.NAZWASEGMENT AS SEGMENT, MR.NAZWAMARKI AS MARKA, MD.NAZWAMODEL AS MODEL, P.NAZWAPALIWO AS PALIWO, MOCKW, SKRZYNIA, LICZBAMIEJSC, ROK, SPRAWNY, DOSTEPNY, AKTYWNY, PRZEBIEG, CENADOBA, CENAKM, UWAGI
        FROM AUTA A
        INNER JOIN MODEL MD ON A.IDMODEL=MD.IDMODEL
        INNER JOIN MARKA MR ON MD.IDMARKA=MR.IDMARKA
        INNER JOIN SEGMENT S ON A.IDSEGMENT=S.IDSEGMENT
        INNER JOIN PALIWO P ON A.IDPALIWO=P.IDPALIWO
        WHERE IDAUTO = :id;');

    $stmt->bindValue(':id', $_SESSION['carEdit'], PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch();

    $fields['marka'] = array_key_exists('marka', $_POST) ? $_POST['marka'] : $row['marka'];
    $fields['model'] = array_key_exists('model', $_POST) ? $_POST['model'] : $row['model'];
    $fields['vin'] = array_key_exists('vin', $_POST) ? $_POST['vin'] : $row['vin'];
    $fields['numer'] = array_key_exists('numer', $_POST) ? $_POST['numer'] : $row['rejestracja'];
    $fields['rok'] = array_key_exists('rok', $_POST) ? $_POST['rok'] : $row['rok'];
    $fields['przebieg'] = array_key_exists('przebieg', $_POST) ? $_POST['przebieg'] : $row['przebieg'];
    $fields['cenakm'] = array_key_exists('cenakm', $_POST) ? $_POST['cenakm'] : $row['cenakm'];
    $fields['cenadoba'] = array_key_exists('cenadoba', $_POST) ? $_POST['cenadoba'] : $row['cenadoba'];
    $fields['dostepny'] = array_key_exists('dostepny', $_POST) ? $_POST['dostepny'] : $row['dostepny'];
    $fields['sprawny'] = array_key_exists('sprawny', $_POST) ? $_POST['sprawny'] : $row['sprawny'];
    $fields['aktywny'] = array_key_exists('aktywny', $_POST) ? $_POST['aktywny'] : $row['aktywny'];
    $fields['uwagi'] = array_key_exists('uwagi', $_POST) ? $_POST['uwagi'] : $row['uwagi'];
    $fields['zdjecie'] = array_key_exists('zdjecie', $_POST) ? $_POST['zdjecie'] : $row['idzdjecie'];


    $errors = array();
    $info = '';

    if(isset($_POST["addPhoto"]))
    {
        redirect(url('imageAdd'));
    }


    if(isset($_POST["acceptAdd"]))
    {

        if (isset($_POST['aktywny'])) {
            $fields['aktywny'] = 'true';

            if (isset($_POST['sprawny'])) {
                $fields['sprawny'] = 'true';

                if (isset($_POST['dostepny'])) {
                    $fields['dostepny'] = 'true';
                } else {
                    $fields['dostepny'] = 'false';
                }
            } else {
                $fields['sprawny'] = 'false';
                $fields['dostepny'] = 'false';
            }
        } else {
            $fields['aktywny'] = 'false';
            $fields['sprawny'] = 'false';
            $fields['dostepny'] = 'false';
        }

        if(empty($fields['numer']))
        {
            $errors['numer'] = 'Pole numer rejestracyjny nie może być puste';
        }
        else
        {
            $fields['numer'] = strtoupper($fields['numer']);
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

                console_log('begin transaction');

                $ustmt = $db->prepare('UPDATE AUTA 
                                        SET REJESTRACJA=:numer, PRZEBIEG = :przebieg, CENAKM = :cenakm, CENADOBA = :cenadoba, DOSTEPNY = :dostepny, SPRAWNY = :sprawny, AKTYWNY = :aktywny, UWAGI = :uwagi, IDZDJECIE = :zdjecie
                                        WHERE IDAUTO = :idauto');
                
                $ustmt -> bindValue(':numer', $fields['numer']);
                $ustmt -> bindValue(':przebieg', $fields['przebieg'], PDO::PARAM_INT);
                $ustmt -> bindValue(':cenakm', $fields['cenakm']);
                $ustmt -> bindValue(':cenadoba', $fields['cenadoba']);
                $ustmt -> bindValue(':uwagi', $fields['uwagi']);
                $ustmt -> bindValue(':zdjecie', $fields['zdjecie'], PDO::PARAM_INT);
                $ustmt -> bindValue(':idauto', $_SESSION['carEdit'], PDO::PARAM_INT);

                $ustmt -> bindValue(':dostepny', $fields['dostepny'], PDO::PARAM_BOOL);
                $ustmt -> bindValue(':sprawny', $fields['sprawny'], PDO::PARAM_BOOL);
                $ustmt -> bindValue(':aktywny', $fields['aktywny'], PDO::PARAM_BOOL);

                $ustmt -> execute();
                console_log('execute');

                $db->commit();
                console_log('commit');
            }
            catch(PDOException $e)
            {
                console_log('rollback');
                $errors['all'] = 'Błąd: ' . $e->getMessage();
                console_log($errors['all']);
                $db->rollBack();
            }

            if (count($errors) == 0) 
            {
                $info = 'Dane zostały zaktualizowane';
                $newid = $_SESSION['carEdit'];
                console_log("Dane dodane pomyślnie");
                unset($_SESSION['carEdit']);
                redirect(url("home&value={$newid}&event=details"));
            }

        }

    }

}

?>