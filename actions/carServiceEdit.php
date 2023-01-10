<?php

if ($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik") 
{
    if((array_key_exists('event', $_GET)))
    {
        if($_GET['event']=='list')
        {
            unset($_SESSION['serviceEdit']);
            redirect(url('carServiceList'));
        }
    }
    
    if (isset($_SESSION['serviceEdit'])) {
        $stmt = $db->query("SELECT idserwis, A.rejestracja, idpracownika, datapoczatek, datakoniec, nazwaserwisu, opis, s.uwagi, koszt
                            FROM serwis S
                            INNER JOIN AUTA A ON S.IDAUTA=A.IDAUTO 
                            WHERE idserwis = {$_SESSION['serviceEdit']}");

        foreach ($stmt as $row) {
            $fields['numer'] = array_key_exists('numer', $_POST) ? $_POST['numer'] : $row['rejestracja'];
            $fields['datapoczatek'] = array_key_exists('datapoczatek', $_POST) ? $_POST['datapoczatek'] : $row['datapoczatek'];
            $fields['datakoniec'] = array_key_exists('datakoniec', $_POST) ? $_POST['datakoniec'] : $row['datakoniec'];
            $fields['nazwaserwis'] = array_key_exists('nazwaserwis', $_POST) ? $_POST['nazwaserwis'] : $row['nazwaserwisu'];
            $fields['opis'] = array_key_exists('opis', $_POST) ? $_POST['opis'] : $row['opis'];
            $fields['uwagi'] = array_key_exists('uwagi', $_POST) ? $_POST['uwagi'] : $row['uwagi'];
            $fields['koszt'] = array_key_exists('koszt', $_POST) ? $_POST['koszt'] : $row['koszt'];
        }

        $errors = array();
        $today = date("Y-m-d");

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
                $_POST['uwagi'] = $_SESSION['user']." dokonał modyfikacji dnia ".date("Y-m-d")."\n";
            }
            else
            {
                $_POST['uwagi'] = $_POST['uwagi']."\n - ".$_SESSION['user']." dokonał modyfikacji dnia ".date("Y-m-d")." -\n";
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
                        $ustmt = $db->prepare("UPDATE serwis 
                                                SET idauta=:idauta, datapoczatek=:datapoczatek, datakoniec=:datakoniec, nazwaserwisu=:nazwaserwisu, opis=:opis, uwagi=:uwagi, koszt=:koszt
                                                WHERE idserwis={$_SESSION['serviceEdit']};");
        
                        $ustmt->bindValue(':idauta', $idauta, PDO::PARAM_INT);
                        $ustmt->bindValue(':datapoczatek', $_POST['datapoczatek']);
                        $ustmt->bindValue(':datakoniec', $_POST['datakoniec']);
                        $ustmt->bindValue(':nazwaserwisu', $_POST['nazwaserwis']);
                        $ustmt->bindValue(':opis', $_POST['opis']);
                        $ustmt->bindValue(':uwagi', $_POST['uwagi']);
                        $ustmt->bindValue(':koszt', $_POST['koszt']);
                        $ustmt->execute();
                    }

                    if($fields['datakoniec'] == $today)
                    {
                        $cstmt = $db->prepare("UPDATE auta SET dostepny=TRUE, sprawny=TRUE WHERE idauto=:idauta;");
                        $cstmt->bindValue(':idauta', $idauta, PDO::PARAM_INT);
                        $cstmt->execute();
                    }
        
                    $db->commit();
                } 
                catch (PDOException $e)
                {
                    $db->rollBack();
                    $errors['all'] = "Błąd: " . $e->getMessage();
                    $info = "";
                }

                if (count($errors) == 0) {
                    unset($_SESSION['serviceEdit']);
                    redirect(url('carServiceList'));
                }
            }
        }
    } 
}
