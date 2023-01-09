<?php

// Pobierz aktualną datę w formacie YYYY-MM-DD
$today = date("Y-m-d");
$errors = array();

if(isset($_SESSION['rentCancel']))
{

    try{
        $db->beginTransaction();

        $pstmt = $db->prepare("SELECT IDWYPOZYCZENIA, IDAUTA, DATAPOCZATEK, DATAKONIEC, PRZEBIEGSTART, PRZEBIEGKONIEC, SUMA, ZAPLACONO, UWAGI
                                FROM WYPOZYCZENIA WHERE IDWYPOZYCZENIA=:id;");

        $pstmt->bindValue(':id', $_SESSION['rentCancel']);
        $pstmt->execute();

        $row = $pstmt -> fetch();

        $uwagi = "ANULOWANO\n".$row['uwagi'];

        $stmt = $db->prepare("UPDATE wypozyczenia
                                SET datakoniec=:datakoniec, przebiegkoniec=:przebiegkoniec, suma=:suma, zaplacono=TRUE, uwagi=:uwagi, realizacja=FALSE
                                WHERE idwypozyczenia=:id;");

        $stmt->bindValue(':id', $_SESSION['rentCancel']);
        $stmt->bindValue(':datakoniec', $row['datapoczatek']);
        $stmt->bindValue(':przebiegkoniec', $row['przebiegstart']);
        $stmt->bindValue(':suma', '0');
        $stmt->bindValue(':uwagi', $uwagi);
        $stmt->execute();

        $carstmt = $db->prepare("UPDATE AUTA
                                SET dostepny=TRUE
                                WHERE idauto=:idauta;");

        $carstmt->bindValue(':idauta', $row['idauta'], PDO::PARAM_INT);           
        $carstmt->execute();

        $db->commit();

    }
    catch (PDOException $e) {
        $db->rollBack();
        $errors['cancel'] = "Błąd: " . $e->getMessage();
        console_log($errors['cancel']);
        unset($_SESSION['rentCancel']);
        //redirect(url("rentList"));
    }

    if (count($errors) == 0) {

        $newid = $_SESSION['rentCancel'];
        console_log("Dane zaktualizowane pomyślnie");
        unset($_SESSION['rentCancel']);
        redirect(url("rentList&value={$newid}&event=details"));
    }

}
