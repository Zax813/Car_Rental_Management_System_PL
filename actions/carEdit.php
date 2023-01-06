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

                $stmt = $db->prepare('UPDATE AUTA 
                                        SET REJESTRACJA=:numer, PRZEBIEG = :przebieg, CENAKM = :cenakm, CENADOBA = :cenadoba, DOSTEPNY = :dostepny, SPRAWNY = :sprawny, AKTYWNY = :aktywny, UWAGI = :uwagi, IDZDJECIE = :zdjecie
                                        WHERE IDAUTO = :idauto');
                
                $stmt -> bindValue(':numer', strtoupper($fields['numer']));
                $stmt -> bindValue(':przebieg', $fields['przebieg'], PDO::PARAM_INT);
                $stmt -> bindValue(':cenakm', $fields['cenakm']);
                $stmt -> bindValue(':cenadoba', $fields['cenadoba']);
                $stmt -> bindValue(':uwagi', $fields['uwagi'], PDO::PARAM_STR);
                $stmt -> bindValue(':zdjecie', $fields['zdjecie'], PDO::PARAM_INT);

                if($fields['aktywny'] == 'true')
                {
                    $stmt -> bindValue(':aktywny', 1, PDO::PARAM_INT);

                    if($fields['sprawny'] == 'true')
                    {
                        $stmt -> bindValue(':sprawny', 1, PDO::PARAM_INT);

                        if ($fields['dostepny'] == 'true') 
                        {
                            $stmt->bindValue(':dostepny', 1, PDO::PARAM_INT);
                        } else 
                        {
                            $stmt->bindValue(':dostepny', 0, PDO::PARAM_INT);
                        }
                    }
                    else
                    {
                        $stmt -> bindValue(':dostepny', 0, PDO::PARAM_INT);
                        $stmt -> bindValue(':sprawny', 0, PDO::PARAM_INT);
                    }
                }
                else
                {
                    $stmt -> bindValue(':aktywny', 0, PDO::PARAM_INT);
                    $stmt -> bindValue(':dostepny', 0, PDO::PARAM_INT);
                    $stmt -> bindValue(':sprawny', 0, PDO::PARAM_INT);
                }

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
                unset($_SESSION['carEdit']);
                redirect(url("home&value={$newid}&event=details"));
            }

        }

    }

}