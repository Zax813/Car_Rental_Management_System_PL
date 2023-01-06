<?php
$errors = array();

if ($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik") 
{
    if (isset($_POST["submit"])) {
        // File upload path
        $folder = "images/";
        $plik = basename($_FILES["file"]["name"]);
        $sciezka = $folder . $plik;
        $rozszerzenie = pathinfo($sciezka, PATHINFO_EXTENSION);

        if (!empty($_FILES["file"]["name"])) {

            // Allow certain file formats
            $dopuszczalne = array('jpg', 'png', 'jpeg');

            if (in_array($rozszerzenie, $dopuszczalne)) {

                // Upload file to server
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $sciezka)) {
                    // Insert image file name into database
                    $stmt = $db->query("INSERT into zdjecia (tytul, sciezka) VALUES ('" . $plik . "', '" . $sciezka . "')");

                    if ($stmt) {
                        $errors['zdjecie'] = "Zdjęcie " . $plik . " zostało załadowane poprawnie.";
                    } else {
                        $errors['zdjecie'] = "Załadowanie zdjęcia do bazy nie powiodło się, spróbuj jeszcze raz.";
                    }
                } else {
                    $errors['zdjecie'] = "Załadowanie zdjęcia na serwer nie powiodło się.";
                }
            } else {
                $errors['zdjecie'] = 'Tylko zdjęcia w formacie JPG, JPEG, PNG.';
            }
        } else {
            $errors['zdjecie'] = 'Wybierz zdjęcie do załadowania.';
        }
    }
}

?>