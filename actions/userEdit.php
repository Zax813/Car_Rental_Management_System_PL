<?php

if ($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik") 
{
    if((array_key_exists('event', $_GET)))
    {
        if($_GET['event']=='list')
        {
            unset($_SESSION['edit']);
            redirect(url('userList'));
        }
    }
    
    if (isset($_SESSION['edit'])) {
        $stmt = $db->query("SELECT * FROM pracownicy WHERE idpracownika = {$_SESSION['edit']}");

        foreach ($stmt as $row) {
            $fields['imie'] = array_key_exists('imie', $_POST) ? $_POST['imie'] : $row['imie'];
            $fields['nazwisko'] = array_key_exists('nazwisko', $_POST) ? $_POST['nazwisko'] : $row['nazwisko'];
            $fields['login'] = array_key_exists('login', $_POST) ? $_POST['login'] : $row['login'];
            $fields['haslo'] = array_key_exists('haslo', $_POST) ? $_POST['haslo'] : '';
            $fields['uprawnienia'] = array_key_exists('uprawnienia', $_POST) ? $row['uprawnienia'] : $row['uprawnienia'];
            $fields['telefon'] = array_key_exists('telefon', $_POST) ? $_POST['telefon'] : $row['telefon'];
            $fields['email'] = array_key_exists('email', $_POST) ? $_POST['email'] : $row['email'];
            $fields['zatrudniony'] = array_key_exists('zatrudniony', $_POST) ? $_POST['zatrudniony'] : $row['zatrudniony'];
        }

        $errors = array();

        if (isset($_POST["accept"])) {
            if (empty($fields['imie'])) {
                $errors['imie'] = 'Pole jest wymagane.';
            }

            if (empty($fields['nazwisko'])) {
                $errors['nazwisko'] = 'Pole jest wymagane.';
            }

            if (empty($fields['login'])) {
                $errors['login'] = 'Pole jest wymagane.';
            }

            if (!empty($fields['telefon'])) {
                if (!validtel($fields['telefon'])) {
                    $errors['telefon'] = 'Numer jest błędny.';
                }
            } else {
                $_POST['telefon'] = NULL;
            }

            if (empty($fields['email'])) {
                $errors['email'] = 'Email jest wymagany';
            } else {
                $fields['email'] = filter_var($fields['email'], FILTER_SANITIZE_EMAIL);

                if (!filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = 'Email jest nieprawidłowy';
                }
            }

            if (count($errors) == 0) {
                try {
                    if (empty($fields['haslo'])) {
                        $ustmt = $db->prepare("UPDATE pracownicy SET login=:login, imie=:imie, nazwisko=:nazwisko, uprawnienia=:uprawnienia, telefon=:telefon, email=:email, zatrudniony=:zatrudniony 
                                                WHERE idpracownika={$_SESSION['edit']}");
                    } else {
                        $ustmt = $db->prepare("UPDATE pracownicy SET login=:login, haslo=:haslo, imie=:imie, nazwisko=:nazwisko, uprawnienia=:uprawnienia, telefon=:telefon, email=:email, zatrudniony=:zatrudniony WHERE idpracownika={$_SESSION['edit']}");
                        $ustmt->bindValue(':haslo', hash('sha256', $_POST['haslo']));
                    }

                    $ustmt->bindValue(':imie', $_POST['imie']);
                    $ustmt->bindValue(':nazwisko', $_POST['nazwisko']);
                    $ustmt->bindValue(':login', $_POST['login']);
                    $ustmt->bindValue(':telefon', $_POST['telefon']);
                    $ustmt->bindValue(':email', $_POST['email']);

                    if (is_admins($db)) {
                        $ustmt->bindValue(':uprawnienia', $_POST['uprawnienia']);
                    } else {
                        $ustmt->bindValue(':uprawnienia', "admin");
                    }

                    if ($_POST['zatrudniony'] == 'true') {
                        $ustmt->bindValue(':zatrudniony', 'true');
                    } else {
                        $ustmt->bindValue(':zatrudniony', 'false');
                    }

                    $ustmt->execute();
                } catch (PDOException $e) {
                    $errors['all'] = "Błąd: " . $e->getMessage();
                }

                if (count($errors) == 0) {
                    unset($_SESSION['edit']);
                    redirect(url('userList'));
                }
            }
        }
    } 
}
