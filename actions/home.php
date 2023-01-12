<?php

unset($_SESSION['carAddStorage']);

if(isset($_SESSION['user']))
{
    
    $items_per_page = 10; //Limit samochodów na stronę

    if (array_key_exists('event', $_GET)) 
    {
        if ($_GET['event'] == "add") 
        {
            $check = $db ->prepare("SELECT IDAUTO, DOSTEPNY
                                    FROM AUTA WHERE IDAUTO = :id;");
            $check -> bindValue(':id', $_GET['value']);
            $check -> execute();
            $row = $check -> fetch();

            if($row['dostepny']==true)
            {
                $_SESSION['rentAdd'] = $_GET['value'];
                redirect(url('rentAdd'));
            }
            else
            {
                redirect(url('home'));
            }
        }

        if ($_GET['event'] == "carAdd") 
        {
            $_SESSION['carAdd'] = true;
            redirect(url('carAdd'));
        }

        if ($_GET['event'] == "edit") 
        {
            $_SESSION['carEdit'] = $_GET['value'];
            redirect(url('carEdit'));
        }

        if ($_GET['event'] == "details") 
        {
            $_SESSION['carDetails'] = $_GET['value'];
            redirect(url('carDetails'));
        }

        if ($_GET['event'] == "history") {
            $_SESSION['carHistory'] = $_GET['value'];
            redirect(url('carHistory'));
        }
    }

    if (array_key_exists('page', $_GET)) 
    {
        if (isset($_GET['page']) && is_numeric($_GET['page'])) 
        {
            $current_page = (int)$_GET['page'];
        } else 
        {
            $current_page = 1;
        }
    }
    else
    {
        $current_page = 1;
    }

    $limit = $items_per_page;
    $offset = ($current_page - 1) * $items_per_page;

    if(isset($_SESSION['filters']))
    {
        $fields['sortuj'] = array_key_exists('sortuj', $_POST) ? $_POST['sortuj'] : $_SESSION['filters']['sortuj'];
        $fields['segment'] = array_key_exists('segment', $_POST) ? $_POST['segment'] : $_SESSION['filters']['segment'];
        $fields['skrzynia'] = array_key_exists('skrzynia', $_POST) ? $_POST['skrzynia'] : $_SESSION['filters']['skrzynia'];
        $fields['paliwo'] = array_key_exists('paliwo', $_POST) ? $_POST['paliwo'] : $_SESSION['filters']['paliwo'];
        $fields['miejscaMin'] = array_key_exists('miejscaMin', $_POST) ? $_POST['miejscaMin'] : $_SESSION['filters']['miejscaMin'];
        $fields['miejscaMax'] = array_key_exists('miejscaMax', $_POST) ? $_POST['miejscaMax'] : $_SESSION['filters']['miejscaMax'];
    }
    else
    {
        $fields['sortuj'] = array_key_exists('sortuj', $_POST) ? $_POST['sortuj'] : '';
        $fields['segment'] = array_key_exists('segment', $_POST) ? $_POST['segment'] : '';
        $fields['skrzynia'] = array_key_exists('skrzynia', $_POST) ? $_POST['skrzynia'] : '';
        $fields['paliwo'] = array_key_exists('paliwo', $_POST) ? $_POST['paliwo'] : '';
        $fields['miejscaMin'] = array_key_exists('miejscaMin', $_POST) ? $_POST['miejscaMin'] : '';
        $fields['miejscaMax'] = array_key_exists('miejscaMax', $_POST) ? $_POST['miejscaMax'] : '';
    }

    $dbwhere = 'A.AKTYWNY IS TRUE';
    $dborderby = '';

    $stmt = $db->query('SELECT * FROM SEGMENT ORDER BY IDSEGMENT;');
    $segmenty = $stmt->fetchAll();

    $stmt = $db->query('SELECT * FROM PALIWO ORDER BY IDPALIWO;');
    $paliwa = $stmt->fetchAll();

    if (isset($fields['sortuj'])) {
        switch ($fields['sortuj']) {
            case "MARKAUP":
                $dborderby = "A.DOSTEPNY DESC, MR.NAZWAMARKI ASC";
                break;

            case "MARKADOWN":
                $dborderby = "A.DOSTEPNY DESC, MR.NAZWAMARKI DESC";
                break;

            case "MOCUP":
                $dborderby = "A.DOSTEPNY DESC, MOCKW ASC";
                break;

            case "MOCDOWN":
                $dborderby = "A.DOSTEPNY DESC, MOCKW DESC";
                break;

            case "CENADOBAUP":
                $dborderby = "A.DOSTEPNY DESC, CENADOBA ASC, CENAKM ASC";
                break;

            case "CENADOBADOWN":
                $dborderby = "A.DOSTEPNY DESC, CENADOBA DESC, CENAKM DESC";
                break;

            case "CENAKMUP":
                $dborderby = "A.DOSTEPNY DESC, CENAKM ASC, CENADOBA ASC";
                break;

            case "CENAKMDOWN":
                $dborderby = "A.DOSTEPNY DESC, CENAKM DESC, CENADOBA DESC;";
                break;

            default:
                $dborderby = "A.DOSTEPNY DESC, S.NAZWASEGMENT ASC, MR.NAZWAMARKI, MD.NAZWAMODEL";
                break;
        }
    } else {
        $dborderby = "A.DOSTEPNY DESC, S.NAZWASEGMENT ASC, MR.NAZWAMARKI, MD.NAZWAMODEL";
    }


    if (!empty($fields['miejscaMin']) && !empty($fields['miejscaMax'])) 
    {
        if ($fields['miejscaMin'] > $fields['miejscaMax'])
        {
            $temp = $_POST['miejscaMin'];
            $_POST['miejscaMin'] = $_POST['miejscaMax'];
            $_POST['miejscaMax'] = $temp;

            $temp = $fields['miejscaMin'];
            $fields['miejscaMin'] = $fields['miejscaMax'];
            $fields['miejscaMax'] = $temp;
            unset($temp);
        }
    }

    if (empty($fields['segment'])) 
    {
        $fields['segment'] = "%";
    }

    if (empty($fields['skrzynia'])) 
    {
        $fields['skrzynia'] = "%";
    }

    if (empty($fields['paliwo'])) 
    {
        $fields['paliwo'] = "%";
    }

    if (empty($fields['miejscaMin'])) 
    {
        $_POST['miejscaMin'] = 0;
    }

    if (empty($fields['miejscaMax'])) 
    {
        $_POST['miejscaMax'] = 100;
    }

    if(array_key_exists('find', $_POST))
    {
        $_SESSION['filters'] = $fields;
    }

    try 
    {

        $pstmt = $db->prepare("SELECT IDAUTO FROM AUTA A
                                WHERE A.AKTYWNY = TRUE
                                AND CAST(A.IDSEGMENT AS TEXT) LIKE :segment
                                AND CAST(A.SKRZYNIA AS TEXT) LIKE :skrzynia
                                AND CAST(A.IDPALIWO AS TEXT) LIKE :paliwo
                                AND A.LICZBAMIEJSC >= :miejscaMin AND A.LICZBAMIEJSC <= :miejscaMax;");

        $pstmt->bindValue(':segment', $fields['segment'], PDO::PARAM_STR);
        $pstmt->bindValue(':skrzynia', $fields['skrzynia'], PDO::PARAM_STR);
        $pstmt->bindValue(':paliwo', $fields['paliwo'], PDO::PARAM_STR);
        $pstmt->bindValue(':miejscaMin', $_POST['miejscaMin'], PDO::PARAM_INT);
        $pstmt->bindValue(':miejscaMax', $_POST['miejscaMax'], PDO::PARAM_INT);
        $pstmt->execute();
        $auta = $pstmt->fetchAll();

        $stmt = $db->prepare("SELECT IDAUTO, VIN, REJESTRACJA, Z.SCIEZKA AS ZDJECIE, Z.TYTUL AS TYTUL, S.NAZWASEGMENT AS SEGMENT, MR.NAZWAMARKI AS MARKA, MD.NAZWAMODEL AS MODEL, P.NAZWAPALIWO AS PALIWO, MOCKW, SKRZYNIA, LICZBAMIEJSC, ROK, SPRAWNY, DOSTEPNY, PRZEBIEG, CENADOBA, CENAKM, UWAGI
            FROM AUTA A
            INNER JOIN MODEL MD ON A.IDMODEL=MD.IDMODEL
            INNER JOIN MARKA MR ON MD.IDMARKA=MR.IDMARKA
            INNER JOIN SEGMENT S ON A.IDSEGMENT=S.IDSEGMENT
            INNER JOIN PALIWO P ON A.IDPALIWO=P.IDPALIWO
            LEFT JOIN ZDJECIA Z ON A.IDZDJECIE=Z.IDZDJECIE
            WHERE A.AKTYWNY = TRUE
            AND CAST(A.IDSEGMENT AS TEXT) LIKE :segment
            AND CAST(A.SKRZYNIA AS TEXT) LIKE :skrzynia
            AND CAST(A.IDPALIWO AS TEXT) LIKE :paliwo
            AND A.LICZBAMIEJSC >= :miejscaMin AND A.LICZBAMIEJSC <= :miejscaMax
            ORDER BY {$dborderby}
            LIMIT :limit
            OFFSET :offset;");

        $stmt->bindValue(':segment', $fields['segment'], PDO::PARAM_STR);
        $stmt->bindValue(':skrzynia', $fields['skrzynia'], PDO::PARAM_STR);
        $stmt->bindValue(':paliwo', $fields['paliwo'], PDO::PARAM_STR);
        $stmt->bindValue(':miejscaMin', $_POST['miejscaMin'], PDO::PARAM_INT);
        $stmt->bindValue(':miejscaMax', $_POST['miejscaMax'], PDO::PARAM_INT);
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();

        $result = $stmt->fetchAll();
    } 
    catch (PDOException $e) {
        $errors['read'] = "Błąd: " . $e->getMessage();
        console_log($errors['read']);
    }

    $total_items = count($auta);
    $total_pages = ceil($total_items / $items_per_page);
    console_log("total_items: " . $total_items);
    console_log("total_pages: " . $total_pages);
}
