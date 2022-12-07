<?php
$fields['search'] = array_key_exists('search', $_POST) ? $_POST['search'] : '';

    if(!empty($_POST['search'])) {
        $search = $_POST['search'];

        $stmt = $db->prepare('SELECT idpracownika,login,imie,nazwisko,uprawnienia,zatrudniony 
            FROM pracownicy
            WHERE login LIKE :keysearch OR imie LIKE :keysearch OR nazwisko LIKE :keysearch;');
    
        $stmt->bindValue(':keysearch', '%' . $search . '%', PDO::PARAM_STR);
        $stmt->execute();
    }
    else{
        $stmt = $db->query('SELECT idpracownika,login,imie,nazwisko,uprawnienia,zatrudniony FROM pracownicy ORDER BY zatrudniony DESC, idpracownika;');
    }


    if((array_key_exists('event', $_GET)))
    {
        if($_GET['event']=="add")
        {
            $_SESSION['edit']=$_GET['value'];
            redirect(url('userEdit'));
        }

        if($_GET['event']=="details")
        {
            $_SESSION['details']=$_GET['value'];
            redirect(url('userDetails'));
        }
    }
