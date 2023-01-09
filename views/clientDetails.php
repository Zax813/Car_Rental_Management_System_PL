<meta charset="utf-8" />

<h2 class='form-outline mx-5 my-2'>Szczegóły Klienta</h2>

<div class='form-outline mx-5 my-2'>
    <table class="table">
        <?php
        foreach ($result as $row)
            echo "<tr>
                <td>ID
                <td>{$row['idklienta']}
            <tr>
                <td>Imię i Nazwisko
                <td>{$row['imie']} {$row['nazwisko']}
            <tr>
                <td>Rodzaj dokumentu
                <td>{$row['rodzajdokumentu']}
            <tr>
                <td>Nr Dokumentu
                <td>{$row['nrdokumentu']}
            <tr>
                <td>Pesel
                <td>{$row['pesel']}
            <tr>
                <td>Kraj
                <td>{$row['kraj']}
            <tr>
                <td>Adres
                <td>{$row['miasto']}, {$row['ulica']} {$row['nrdomu']}";

                if($row['nrmieszkania'] != NULL || $row['nrmieszkania'] != "")
                {echo " / {$row['nrmieszkania']}";}

            echo "
            <tr>
                <td>Kod Pocztowy
                <td>{$row['kodpocztowy']}
            <tr>
                <td>Telefon
                <td>{$row['telefon']}
            <tr>
                <td>E-mail
                <td>{$row['email']}
            <tr>
                <td>Uwagi
                <td><textarea class='col-md-12' id='uwagi' name='uwagi' rows='5' cols='40' disabled>{$row['uwagi']}</textarea>
        ";
        ?>
    </table>
</div>
<div class='form-outline mx-5 my-2'>
    <?php
    if ($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik") {
        echo "<a class='btn btn-info btn-sm mr-1' href='index.php?action=clientDetails&event=back' title='Lista' name='list'><i class='bi bi-arrow-left-circle'></i> Powrót</a>";
        echo "<a class='btn btn-warning btn-sm mx-2' href='index.php?action=clientDetails&event=edit' title='Edytuj' name='edit'><i class='bi bi-pencil-square'></i> Edytuj</a>";
    }
    ?>
</div>