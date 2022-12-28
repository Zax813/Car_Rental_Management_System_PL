<meta charset="utf-8" />

<h2 class='form-outline mx-5 my-2'>Szczegóły Wypożyczenia</h2>

<div class='form-outline mx-5 my-2'>
    <table class="table">
        <?php
            echo "<tr>
                <td>ID
                <td>{$row['idwypozyczenia']}
            <tr>
                <td>Samochód
                <td><a href='index.php?action=rentDetails&event=car'>{$row['marka']} {$row['model']} ({$row['rejestracja']})</a>
            <tr>
                <td>Klient
                <td><a href='index.php?action=rentDetails&event=client'>{$row['imie']} {$row['nazwisko']}</a>
            <tr>
                <td>Kontakt
                <td>{$row['telefon']} | | {$row['email']}
            <tr>
                <td>Pracownik
                <td><a href='index.php?action=rentDetails&event=user'>{$row['pracimie']} {$row['pracnazwisko']} ({$row['login']})
            <tr>
                <td>Czas wypożyczenia
                <td>{$row['datapoczatek']} - {$row['datakoniec']} ($dni dni)
            <tr>
                <td>Przebieg
                <td> {$row['przebiegstart']} - {$row['przebiegkoniec']} ($przejechano km)
            <tr>
                <td>Koszt
                <td>{$row['suma']}
            <tr>
                <td>Opłacone";
                if($row['zaplacono'] == false)
                {
                    echo "<td style='color: red'>Nie<td>";
                }
                else
                {
                    echo "<td style='color: green'>Tak<td>";
                }
            
            echo "<tr>
                <td>Uwagi
                <td>{$row['uwagi']}

        "
        ?>
    </table>
</div>
<div class='form-outline mx-5 my-2'>
    <?php
    if ($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik") {
        echo "<a class='btn btn-info btn-sm mr-1' href='index.php?action=carServiceDetails&event=back' title='Lista' name='list'><i class='bi bi-arrow-left-circle'></i> Powrót</a>";
        echo "<a class='btn btn-warning btn-sm mx-2' href='index.php?action=carServiceDetails&event=edit' title='Edytuj' name='edit'><i class='bi bi-pencil-square'></i> Edytuj</a>";
    }
    ?>
</div>