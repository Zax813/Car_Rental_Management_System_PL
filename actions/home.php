<?php
/*
$fields['limit'] = array_key_exists('limit', $_POST) ? $_POST['limit'] : '50';
$fields['typ'] = array_key_exists('typ', $_POST) ? $_POST['typ'] : '';
$fields['rodzaj'] = array_key_exists('rodzaj', $_POST) ? $_POST['rodzaj'] : '';
$fields['fmarka'] = array_key_exists('fmarka', $_POST) ? $_POST['fmarka'] : '';
$fields['sortuj'] = array_key_exists('sortuj', $_POST) ? $_POST['sortuj'] : 'KOD';
$errors = array();
*/

$dbmarka = $db->query('SELECT * FROM AUTA ORDER BY IDSEGMENT;');
$dbtyp = $db->query('SELECT * FROM MODEL;');

try 
{
    $stmt = $db->prepare("SELECT IDAUTO, VIN, REJESTRACJA, Z.SCIEZKA AS ZDJECIE, Z.TYTUL AS TYTUL, S.NAZWASEGMENT AS SEGMENT, MR.NAZWAMARKI AS MARKA, MD.NAZWAMODEL AS MODEL, P.NAZWAPALIWO AS PALIWO, MOCKW, SKRZYNIA, LICZBAMIEJSC, ROK, SPRAWNY, DOSTEPNY, PRZEBIEG, CENADOBA, CENAKM, UWAGI
        FROM AUTA A
        INNER JOIN MODEL MD ON A.IDMODEL=MD.IDMODEL
        INNER JOIN MARKA MR ON MD.IDMARKA=MR.IDMARKA
        INNER JOIN SEGMENT S ON A.IDSEGMENT=S.IDSEGMENT
        INNER JOIN PALIWO P ON A.IDPALIWO=P.IDPALIWO
        LEFT JOIN ZDJECIA Z ON A.IDZDJECIE=Z.IDZDJECIE
        ORDER BY S.NAZWASEGMENT, MR.NAZWAMARKI, MD.NAZWAMODEL;");
    
    $stmt->execute();

} 
catch (PDOException $e) {
    $errors['read'] = "Błąd: " . $e->getMessage();
}



////Stary kod////
/*
if(empty($fields['typ']))
{
    $_POST['typ']="%";
}

if (!empty($fields['typ'])) 
{
    $dbrodzaj1 = $db->query("SELECT IDRODZAJ, NAZWARODZAJ FROM RODZAJ WHERE IDTYP={$fields['typ']};");
    $dbrodzaj=$dbrodzaj1->fetchAll();
    if(!in_array_r($_POST['rodzaj'], $dbrodzaj))
    {
        $fields['rodzaj']='';
    }
}

switch($fields['sortuj'])
{
    case "KOD":
        $_POST['sortuj']="KOD";
        break;

    case "MODEL":
        $_POST['sortuj']="MODEL";
        break;

    case "CENA1":
        $_POST['sortuj']="CENA";
        break;

    case "CENA2":
        $_POST['sortuj']="CENA DESC";
        break;
    
    default:
        $_POST['sortuj']="KOD";
        break;
}

if(empty($fields['typ']))
{
    $_POST['typ']="%";
}

if(empty($fields['rodzaj']))
{
    $_POST['rodzaj']="%";
}

if(empty($fields['fmarka']))
{
    $_POST['fmarka']="%";
}

if(isset($_POST['filtruj']))
{
    try {
        $stmt = $db->prepare("SELECT KOD, T.NAZWATYP AS TYP, R.NAZWARODZAJ AS RODZAJ, M.NAZWA AS MARKA, MODEL, OPIS, ILOSC, CENA, PRODUKOWANY
                            FROM SPRZET S
                            INNER JOIN MARKA M ON S.IDMARKA=M.IDMARKA
                            INNER JOIN RODZAJ R ON S.IDRODZAJ=R.IDRODZAJ
                            INNER JOIN TYP T ON R.IDTYP=T.IDTYP
                            WHERE CAST(T.IDTYP AS TEXT) LIKE :typ 
                            AND CAST(S.IDRODZAJ AS TEXT) LIKE :rodzaj 
                            AND CAST(M.IDMARKA AS TEXT) LIKE :marka 
                            AND (ILOSC>0 OR PRODUKOWANY=TRUE)
                            ORDER BY {$_POST['sortuj']}
                            LIMIT :limit ;");
        
        $stmt->bindValue(':typ', $_POST['typ']);
        $stmt->bindValue(':rodzaj', $_POST['rodzaj']);
        $stmt->bindValue(':marka', $_POST['fmarka']);
        $stmt->bindValue(':limit', $fields['limit']);
        $stmt->execute();

    } catch (PDOException $e) {
        $errors['read'] = "Błąd: " . $e->getMessage();
    }
}
else
{
    $stmt = $db->query("SELECT KOD, T.NAZWATYP AS TYP, R.NAZWARODZAJ AS RODZAJ, M.NAZWA AS MARKA, MODEL, OPIS, ILOSC, CENA, PRODUKOWANY
                            FROM SPRZET S
                            INNER JOIN MARKA M ON S.IDMARKA=M.IDMARKA
                            INNER JOIN RODZAJ R ON S.IDRODZAJ=R.IDRODZAJ
                            INNER JOIN TYP T ON R.IDTYP=T.IDTYP
                            WHERE ILOSC>0 OR PRODUKOWANY=TRUE
                            ORDER BY KOD
                            LIMIT 50;");

}


if (isset($_POST['add'])) {
    if (isset($_SESSION['cart'])) {
        $item_array_id = array_column($_SESSION["cart"], "kod");
        if (!in_array($_GET["kod"], $item_array_id)) {
            if (empty($_POST["liczba"])) {
                $_POST["liczba"] = '1';
            }
            $count = count($_SESSION["cart"]);
            $item_array = array(
                'kod' => $_GET["kod"],
                'marka' => $_POST["marka"],
                'model' => $_POST["model"],
                'liczba' => $_POST["liczba"],
                'cena' => $_POST["cena"]
            );
            $_SESSION["cart"][$count] = $item_array;
        } else {
            echo '<script>alert("Przedmiot został już dodany!")</script>';
        }
    } else {
        if (empty($_POST["liczba"])) {
            $_POST["liczba"] = '1';
        }
        $item_array = array(
            'kod' => $_GET["kod"],
            'marka' => $_POST["marka"],
            'model' => $_POST["model"],
            'liczba' => $_POST["liczba"],
            'cena' => $_POST["cena"]
        );
        $_SESSION["cart"][0] = $item_array;
    }
}

if (isset($_POST['edit'])) {
    $_SESSION['equip'] = $_GET['kod'];
    redirect(url("editEquip"));
}
*/