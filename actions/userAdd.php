<?php

if ($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik") 
{
    if((array_key_exists('event', $_GET)))
    {
        if($_GET['event']=='list')
        {
            redirect(url('userList'));
        }
    }


    $fields['imie'] = array_key_exists('imie', $_POST) ? $_POST['imie'] : '';
    $fields['nazwisko'] = array_key_exists('nazwisko', $_POST) ? $_POST['nazwisko'] : '';
    $fields['login'] = array_key_exists('login', $_POST) ? $_POST['login'] : '';
    $fields['haslo'] = array_key_exists('haslo', $_POST) ? $_POST['haslo'] : '';
    $fields['powtorzhaslo'] = array_key_exists('powtorzhaslo', $_POST) ? $_POST['powtorzhaslo'] : '';
    $fields['uprawnienia'] = array_key_exists('uprawnienia', $_POST) ? $_POST['uprawnienia'] : '';
    $fields['telefon'] = array_key_exists('telefon', $_POST) ? $_POST['telefon'] : '';
    $fields['email'] = array_key_exists('email', $_POST) ? $_POST['email'] : '';
    $errors = array();
    $info = "";

    if(isset($_POST["acceptAdd"])) 
    {
        if (empty($fields['imie']))
        {
            $errors['imie'] = 'Pole jest wymagane.';
        }

        if (empty($fields['nazwisko']))
        {
            $errors['nazwisko'] = 'Pole jest wymagane.';
        }

        if (empty($fields['login'])) 
        {
            $errors['login'] = 'Pole jest wymagane.';
        }

        if (empty($fields['haslo'])) 
        {
            $errors['haslo'] = 'Pole jest wymagane.';
        }

        if (!empty($fields['powtorzhaslo'])) 
        {
            if($fields['powtorzhaslo'] != $fields['haslo'])
            {
                $errors['powtorzhaslo'] = 'Hasła nie są zgodne.';
            }
        }
        else
        {
            $errors['powtorzhaslo'] = 'Pole jest wymagane.';
        }

        if (!empty($fields['telefon'])) {
            if (!validtel($fields['telefon'])) {
                $errors['telefon'] = 'Numer jest błędny.';
            }
        }
        else
        {
            $_POST['telefon']=NULL;
        }

        if (empty($fields['email'])) {
            $errors['email'] = 'Email jest wymagany';
        } else {
            $fields['email'] = filter_var($fields['email'], FILTER_SANITIZE_EMAIL);

            if (!filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email jest nieprawidłowy';
            }
        }

        if(count($errors)==0)
        {
            try
            {
                $db -> beginTransaction();

                $ustmt = $db->prepare("INSERT INTO pracownicy (imie, nazwisko, login, haslo, uprawnienia, email, telefon) 
                                    VALUES (:imie, :nazwisko, :login, :haslo, :uprawnienia, :email, :telefon)");

                $ustmt->bindValue(':imie', $_POST['imie']);
                $ustmt->bindValue(':nazwisko', $_POST['nazwisko']);
                $ustmt->bindValue(':login', $_POST['login']);
                $ustmt->bindValue(':haslo', hash('sha256',$_POST['haslo']));
                $ustmt->bindValue(':uprawnienia', $_POST['uprawnienia']);
                $ustmt->bindValue(':telefon', $_POST['telefon']);
                $ustmt->bindValue(':email', $_POST['email']);
                $ustmt->execute();

                $db -> commit();
            } 
            catch (PDOException $e) 
            {
                $db -> rollBack();
                $errors['all'] = "Błąd: ".$e->getMessage();
                $info="";
            }

            if (count($errors) == 0)
            {
                $info="Pracownik dodany pomyślnie";
            }
        }
    }
}

?>
