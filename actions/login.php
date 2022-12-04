<?php

$fields['username'] = array_key_exists('username', $_POST) ? $_POST['username'] : '';
$fields['password'] = array_key_exists('password', $_POST) ? $_POST['password'] : '';
$errors = array();


if (count($_POST) > 0) 
{
    if (empty($fields['username'])) 
    {
        $errors['username'] = 'Pole jest wymagane.';
    }
   
    if (empty($fields['password'])) 
    {
        $errors['password'] = 'Pole jest wymagane.';
    }
    
    try
    {
        $query = "SELECT * FROM pracownicy WHERE login=:username AND haslo=:password";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
        $stmt->bindValue(':password', hash('sha256',$_POST['password']), PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(empty($row))
        {
            $errors['all'] = 'Niepoprawne dane logowania.';
        } 
        else 
        {
            if($row['zatrudniony']==0)
            {
                $errors['all'] = 'Brak dostępu.';
            }
            else
            {
                $_SESSION['uid'] = $row['idpracownika'];
                $_SESSION['user'] = $row['login'];
                $_SESSION['uname'] = $row['imie'];
                $_SESSION['perm'] = $row['uprawnienia'];
            }
        }
    } 
    catch (PDOException $e) 
    {
        $errors['all'] = "Błąd: ".$e->getMessage();
    }

    if (count($errors) == 0)
    {
        redirect(url('home'));
    }
}
   
?>