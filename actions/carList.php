<?php

if(isset($_SESSION['user']))
{
    if($_SESSION['perm']=='admin')
    {

        if ((array_key_exists('event', $_GET))) 
        {
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
        }


        $stmt = $db->prepare("SELECT IDAUTO, REJESTRACJA, S.NAZWASEGMENT AS SEGMENT, MR.NAZWAMARKI AS MARKA, MD.NAZWAMODEL AS MODEL, P.NAZWAPALIWO AS PALIWO, MOCKW, SKRZYNIA, LICZBAMIEJSC, ROK, SPRAWNY, DOSTEPNY, AKTYWNY, PRZEBIEG, CENADOBA, CENAKM, UWAGI
                    FROM AUTA A
                    INNER JOIN MODEL MD ON A.IDMODEL=MD.IDMODEL
                    INNER JOIN MARKA MR ON MD.IDMARKA=MR.IDMARKA
                    INNER JOIN SEGMENT S ON A.IDSEGMENT=S.IDSEGMENT
                    INNER JOIN PALIWO P ON A.IDPALIWO=P.IDPALIWO;");

        $stmt->execute();
        $result = $stmt->fetchAll();
        
    }
}